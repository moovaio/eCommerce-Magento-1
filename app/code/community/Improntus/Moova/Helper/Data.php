<?php

/**
 * Class Improntus_Moova_Helper_Data
 *
 * @author Improntus <http://www.improntus.com> - Ecommerce done right
 */
class Improntus_Moova_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getWebserviceUser()
    {
        return Mage::getStoreConfig('shipping/moova_webservice/user',Mage::app()->getStore());
    }

    /**
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getWebservicePass()
    {
        return Mage::getStoreConfig('shipping/moova_webservice/password',Mage::app()->getStore());
    }

    /**
     * @param $carrier
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getPesoMaximo($carrier)
    {
        return Mage::getStoreConfig("carriers/$carrier/max_package_weight",Mage::app()->getStore());
    }

    /**
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getApiUrl()
    {
        return Mage::getStoreConfig('shipping/moova_webservice/url',Mage::app()->getStore());
    }

    /**
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getPromocode()
    {
        return Mage::getStoreConfig('shipping/moova_webservice/promocode',Mage::app()->getStore());
    }

    /**
     * @return array
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getDireccionRetiro()
    {
        return array(
            'calle' => Mage::getStoreConfig('shipping/direccion/calle',Mage::app()->getStore()),
            'numero' => Mage::getStoreConfig('shipping/direccion/numero',Mage::app()->getStore()),
            'piso' => Mage::getStoreConfig('shipping/direccion/piso',Mage::app()->getStore()),
            'departamento' => Mage::getStoreConfig('shipping/direccion/departamento',Mage::app()->getStore()),
            'ciudad' => Mage::getStoreConfig('shipping/direccion/ciudad',Mage::app()->getStore()),
            'provincia' => Mage::getStoreConfig('shipping/direccion/provincia',Mage::app()->getStore()),
            'codigo_postal' => Mage::getStoreConfig('shipping/direccion/codigo_postal',Mage::app()->getStore()),
            'observaciones' => Mage::getStoreConfig('shipping/direccion/observaciones',Mage::app()->getStore())

        );
    }

    /**
     * @param $regionId
     * @return string
     */
    public function getProvincia($regionId)
    {
        if(is_int($regionId))
        {
            $provincia = Mage::geModel('directory/region')->load($regionId);

            $regionId = $provincia->getName() ? $provincia->getName() : $regionId;
        }

        return $regionId;
    }

    /**
     * @param $MoovaQuoteId
     */
    public function setMoovaQuoteId($MoovaQuoteId)
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $quote->setMoovaQuoteId($MoovaQuoteId);
        $quote->save();
    }

    /**
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getSecretKey()
    {
        return Mage::getStoreConfig('shipping/moova_webservice/secret_key',Mage::app()->getStore());
    }

    /**
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getAppId()
    {
        return Mage::getStoreConfig('shipping/moova_webservice/app_id',Mage::app()->getStore());
    }

    /**
     * @param string $shipmentMoovaId
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getTrackingPopupUrlBySalesModel($shipmentMoovaId)
    {
        return Mage::getStoreConfig('shipping/moova_webservice/dashboard_url',Mage::app()->getStore()) . "external?id=$shipmentMoovaId";
    }
}