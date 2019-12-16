<?php

/**
 * Class Improntus_Moova_Model_Carrier_Moova
 *
 * @author Improntus <http://www.improntus.com> - Ecommerce done right
 */
class Improntus_Moova_Model_Carrier_Moova extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    const CARRIER_CODE = 'moova';

    /**
     * Carrier's code
     *
     * @var string
     */
    protected $_code = self::CARRIER_CODE;

    /**
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return bool|false|Mage_Core_Model_Abstract|Mage_Shipping_Model_Rate_Result|null
     * @throws Mage_Core_Model_Store_Exception
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        /** @var Improntus_Moova_Helper_Data $helper */
        $helper = Mage::helper('moova');
        $webservice = Mage::getModel('moova/webservice');

        $pesoTotal = 0;
        $freeBoxes = 0;

        $pesoMaximo = (float)$helper->getPesoMaximo(self::CARRIER_CODE);
        $sku = '';

        $itemsWsMoova = [];

        foreach ($request->getAllItems() as $_item)
        {
            if($sku != $_item->getSku())
            {
                $sku = $_item->getSku();
                $pesoTotal = ($_item->getQty() * $_item->getWeight()) + $pesoTotal;
                $_producto = $_item->getProduct();

                if ($_item->getFreeShippingDiscount() && !$_item->getProduct()->isVirtual())
                {
                    $freeBoxes += $_item->getQty();
                }

                $itemsWsMoova[] = [
                    'description' => $_item->getName(),
                    'price'     => $_item->getPrice(),
                    'weight'    => ($_item->getQty() * $_item->getWeight()),
                    'length'    => (int) $_producto->getResource()
                            ->getAttributeRawValue($_producto->getId(),'alto',$_producto->getStoreId()) * $_item->getQty(),
                    'width'     => (int) $_producto->getResource()
                            ->getAttributeRawValue($_producto->getId(),'largo',$_producto->getStoreId()) * $_item->getQty(),
                    'height'    => (int) $_producto->getResource()
                            ->getAttributeRawValue($_producto->getId(),'ancho',$_producto->getStoreId()) * $_item->getQty()
                ];
            }
        }

        if(isset($freeBoxes))
            $this->setFreeBoxes($freeBoxes);

        $result = Mage::getModel('shipping/rate_result');

        if($pesoTotal >= $pesoMaximo)
        {
            $error = Mage::getModel('shipping/rate_result_error');
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage('Su pedido supera el peso máximo permitido por Moova. Por favor divida su orden en más pedidos o consulte al administrador de la tienda.');
            return $error;
        }
        else
        {
            $address =  Mage::app()->getRequest()->getParam('billing') ?  Mage::app()->getRequest()->getParam('billing') :
                Mage::app()->getRequest()->getParam('shipping');

            $quote = Mage::getSingleton('checkout/session')->getQuote();

            if(!$address)
            {
                $shippingAddress = $quote->getShippingAddress();

                $address = [];
                $address['firstname'] = $shippingAddress->getFirstname();
                $address['lastname'] = $shippingAddress->getLastname();
                $address['mail'] = $shippingAddress->getEmail();
                $address['telephone'] = $shippingAddress->getTelephone();
                $address['street'] = [];

                foreach ($shippingAddress->getStreet() as $_street)
                {
                    $address['street'][] = $_street;
                }

                $address['altura'] = $shippingAddress->getAltura();
                $address['city'] = $shippingAddress->getCity();
                $address['region_id'] = $shippingAddress->getRegionId();
                $address['region'] = $shippingAddress->getRegion();
                $address['piso'] = $shippingAddress->getPiso();
                $address['departamento'] = $shippingAddress->getDepartamento();
                $address['postcode'] = $shippingAddress->getPostcode();
            }else{
                $shippingAddress = $quote->getShippingAddress();
                $address['altura'] = $shippingAddress->getAltura();
                $address['piso'] = $shippingAddress->getPiso();
                $address['departamento'] = $shippingAddress->getDepartamento();
                $address['postcode'] = $shippingAddress->getPostcode();
            }

            $direccionRetiro = $helper->getDireccionRetiro();
            $countryIso3Code = Mage::getModel('directory/country')->load($shippingAddress->getCountryId())->getIso3Code();

            $costoEnvio = $webservice->getBudget(
                [
                    'from' => [
                        'street' => $direccionRetiro['calle'],
                        'number' => $direccionRetiro['numero'],
                        'floor'  => $direccionRetiro['piso'],
                        'apartment' => $direccionRetiro['departamento'],
                        'city'      => $direccionRetiro['ciudad'],
                        'state'      => $direccionRetiro['provincia'],
                        'postalCode' => $direccionRetiro['codigo_postal'],
                        'country' => $countryIso3Code,
                    ],
                    'to' => [
                        'street'   => trim(implode(' ',$address['street'])),
                        'number'   => $address['altura'],
                        'floor'    => $address['piso'],
                        'apartment'  => $address['departamento'],
                        'city'       => $address['city'],
                        'state'      => $address['region_id'] ? $helper->getProvincia($address['region_id']) : $address['region'],
                        'postalCode' => $address['postcode'],
                        'country'    => $countryIso3Code
                    ],
                    'conf'=>[
                        'assurance' => false,
                        'items'     => $itemsWsMoova
                    ],
                    'type' => 'magento_1_24_horas_max'
                ],1);

            if($costoEnvio)
            {
                /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
                $rate = Mage::getModel('shipping/rate_result_method');

                $rate->setCarrier($this->_code);
                $rate->setCarrierTitle($this->getConfigData('title'));
                $rate->setMethod($this->_code);
                $rate->setMethodTitle($this->getConfigData('description'));

                if($request->getFreeShipping() == true || $request->getPackageQty() == $this->getFreeBoxes())
                {
                    $costoEnvio = '0.00';
                }

                $rate->setPrice($costoEnvio);
                $rate->setCost($costoEnvio);

                $result->append($rate);
            }
            else
            {
                $error = Mage::getModel('shipping/rate_result_error');
                $error->setCarrier($this->_code);
                $error->setCarrierTitle($this->getConfigData('title'));
                $error->setErrorMessage('No existen cotizaciones para la dirección ingresada');
                $error->setMethodDescription($this->getConfigData('description'));

                return $error;
            }
        }

        return $result;
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods() 
    {
        return array($this->_code => $this->getConfigData('name'));
    }
}

