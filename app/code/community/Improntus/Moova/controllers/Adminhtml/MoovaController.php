<?php

/**
 * Class Improntus_Moova_Adminhtml_MoovaController
 *
 * @author Improntus <http://www.improntus.com> - Ecommerce done right
 */
class Improntus_Moova_Adminhtml_MoovaController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @throws Exception
     */
    public function solicitarAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        /** @var Mage_Sales_Model_Order $order */
        $order = Mage::getModel('sales/order')->load($orderId);

        if (!$order->getId())
        {
            Mage::throwException("Order does not exist, for the Shipment process to complete");
        }

        if ($order->canShip())
        {
            try
            {
                $shipment = Mage::getModel('sales/service_order', $order)
                    ->prepareShipment($this->_getItemQtys($order));

                $shippingAddress = $order->getShippingAddress();

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

                $sku = '';

                $itemsWsMoova = [];

                foreach ($order->getAllItems() as $_item)
                {
                    if($sku != $_item->getSku())
                    {
                        $sku = $_item->getSku();

                        $_producto = $_item->getProduct();

                        $itemsWsMoova[] = [
                            'quantity'  => $_item->getQtyOrdered(),
                            'description' => $_item->getName(),
                            'price'     => $_item->getPrice(),
                            'weight'    => ($_item->getQtyOrdered() * $_item->getWeight()),
                            'length'    => (int) $_producto->getResource()
                                    ->getAttributeRawValue($_producto->getId(),'alto',$_producto->getStoreId()) * $_item->getQtyOrdered(),
                            'width'     => (int) $_producto->getResource()
                                    ->getAttributeRawValue($_producto->getId(),'largo',$_producto->getStoreId()) * $_item->getQtyOrdered(),
                            'height'    => (int) $_producto->getResource()
                                    ->getAttributeRawValue($_producto->getId(),'ancho',$_producto->getStoreId()) * $_item->getQtyOrdered()
                        ];
                    }
                }

                $countryIso3Code = Mage::getModel('directory/country')->load($order->getShippingAddress()->getCountryId())->getIso3Code();

                $helper = Mage::helper('moova');
                $direccionRetiro = $helper->getDireccionRetiro();

                $shippingParams = [
                    'currency'      => $order->getOrderCurrency()->getCode(),
                    'type'          => 'magento_1_24_horas_max',
                    'flow'          => 'manual',
                    'from'          =>
                        [
                            'googlePlaceId' => '',
                            'country'       => $countryIso3Code,
                            'street' => $direccionRetiro['calle'],
                            'number' => $direccionRetiro['numero'],
                            'floor'  => $direccionRetiro['piso'],
                            'apartment' => $direccionRetiro['departamento'],
                            'city'      => $direccionRetiro['ciudad'],
                            'state'      => $direccionRetiro['provincia'],
                            'postalCode' => $direccionRetiro['codigo_postal'],
                            'instructions'  => '',
                            'contact'       =>
                                [
                                    'firstName' => '',
                                    'lastName'  => '',
                                    'email'     => '',
                                    'phone'     => ''
                                ],
                            'message'=> ''
                        ],
                    'to'=>
                        [
                            'googlePlaceId' => '',
                            'street'   => trim(implode(' ',$address['street'])),
                            'number'   => $address['altura'],
                            'floor'    => $address['piso'],
                            'apartment'  => $address['departamento'],
                            'city'       => $address['city'],
                            'state'      => $address['region_id'] ? $helper->getProvincia($address['region_id']) : $address['region'],
                            'postalCode' => $address['postcode'],
                            'country'       => $countryIso3Code,
                            'instructions'  => $shippingAddress->getObservaciones(),
                            'contact'=> [
                                'firstName' => $shippingAddress->getFirstname(),
                                'lastName'  => $shippingAddress->getLastname(),
                                'email'     => $shippingAddress->getEmail(),
                                'phone'     => $shippingAddress->getTelephone()
                            ],
                            'message'       => ''
                        ],
                    'internalCode'  => '',
                    'comments'      => '',
                    'extra'         => [],
                    'conf' =>
                        [
                            'assurance' => false,
                            'items'     => $itemsWsMoova
                        ],
                ];

                /** @var Improntus_Moova_Model_Webservice $MoovaWs */
                $MoovaWs = Mage::getModel('moova/webservice');
                $shipmentMoova = $MoovaWs->newShipment($shippingParams);

                if($shipmentMoova === false)
                {
                    return false;
                }

                $shipmentId = substr($shipmentMoova['id'],0,8);

                $arrTracking = array(
                    'carrier_code' => isset($carrier_code) ? $carrier_code : $order->getShippingCarrier()->getCarrierCode(),
                    'title' => isset($shipmentCarrierTitle) ? $shipmentCarrierTitle : $order->getShippingCarrier()->getConfigData('title'),
                    'number' => $shipmentId,
                );

                $track = Mage::getModel('sales/order_shipment_track')->addData($arrTracking);
                $shipment->addTrack($track);

                $shipment->register();

                $this->_saveShipment($shipment, $order, 'La solicitud de envío MOOVA fue realizada exitosamente.');

                $order->save();

                Mage::getSingleton("core/session")->addSuccess('La solicitud de envío MOOVA fue realizada exitosamente.');
            }
            catch (Exception $e)
            {
                Mage::getSingleton("core/session")->addError('Se produjo un error al intentar generar el envío MOOVA. Error: '.$e->getMessage());

                throw $e;
            }
        }
        else
        {
            Mage::getSingleton("core/session")->addNotice('El pedido no puede ser generado en MOOVA');
        }

        $this->_redirectReferer();
    }

    public function descargarAction()
    {
        $request = $this->getRequest();
        $moovaId = $request->getParam('moova_id');

        try
        {
            $shipment = Mage::getModel('moova/webservice')->getShipmentLabel($moovaId);

            if($shipment !== false && $shipment['status'] != 'error')
            {
                $url = $shipment['label'];
                $mediapath = Mage::getBaseDir('media') . '/moova/';
                $file = $mediapath . basename($url);

                if (!file_exists($mediapath) || !is_dir($mediapath))
                {
                    mkdir("{$mediapath}", 0775,true);
                }

                $fp = fopen($file, 'w');

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_FILE, $fp);

                $data = curl_exec($ch);

                curl_close($ch);
                fclose($fp);

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
                return;
            }

            if(isset($shipment['status']) && $shipment['status'] == 'error')
            {
                Mage::getSingleton("core/session")->addError(__('Se produjo un error al generar el envío MOOVA. Por favor intentelo nuevamente. '. $shipment['message']));
            }
            else{
                Mage::getSingleton("core/session")->addError(__('Se produjo un error al generar el envío MOOVA. Por favor intentelo nuevamente'));
            }
        }
        catch (Exception $e)
        {
            Mage::getSingleton("core/session")->addError('Se produjo un error al intentar generar el envío MOOVA. Error: '.$e->getMessage());
        }

        $this->_redirectReferer();
    }

    public function enviarAction()
    {
        $request = $this->getRequest();
        $moovaId = $request->getParam('moova_id');
        $status  = $request->getParam('status');

        try
        {
            $shipment = Mage::getModel('moova/webservice')->sendStatusShipment($moovaId, $status);

            if($shipment === true)
                Mage::getSingleton("core/session")->addSuccess(__('Se envio con exito el estado '.$status.' al envio MOOVA con id '.$moovaId));
            else
                Mage::getSingleton("core/session")->addError(__('Se produjo un error, por favor revice la dirección en el panel de Moova, puede referirse a múltiples lugares.'));
        }
        catch (Exception $e)
        {
            Mage::getSingleton("core/session")->addError(__('Se produjo un error: ') . $e->getMessage());
        }

        $this->_redirectReferer();
    }

    /**
     * @param Mage_Sales_Model_Order_Shipment $shipment
     * @param Mage_Sales_Model_Order $order
     * @param string $customerEmailComments
     * @return $this
     * @throws Exception
     */
    public function _saveShipment(Mage_Sales_Model_Order_Shipment $shipment, Mage_Sales_Model_Order $order, $customerEmailComments = '')
    {
        $shipment->getOrder()->setIsInProcess(true);
        $transactionSave = Mage::getModel('core/resource_transaction')
            ->addObject($shipment)
            ->addObject($order)
            ->save();

        $emailSentStatus = $shipment->getData('email_sent');
        $ship_data = $shipment->getOrder()->getData();
        $customerEmail = $ship_data['customer_email'];

        if (!is_null($customerEmail) && !$emailSentStatus)
        {
            $shipment->sendEmail(true, $customerEmailComments);
            $shipment->setEmailSent(true);
        }

        return $this;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return array
     */
    public function _getItemQtys(Mage_Sales_Model_Order $order)
    {
        $qty = array();

        foreach ($order->getAllItems() as $_eachItem)
        {
            if ($_eachItem->getParentItemId())
            {
                $qty[$_eachItem->getParentItemId()] = $_eachItem->getQtyOrdered();
            }
            else
            {
                $qty[$_eachItem->getId()] = $_eachItem->getQtyOrdered();
            }
        }

        return $qty;
    }
}