<?php

/**
 * Class Improntus_Moova_Model_Webservice
 *
 * @author Improntus <http://www.improntus.com> - Ecommerce done right
 */
class Improntus_Moova_Model_Webservice
{
    /**
     * @var string
     */
    protected $_user;

    /**
     * @var string
     */
    protected $_pass;

    /**
     * @var string
     */
    protected $_apiUrl;

    /**
     * @var Improntus_Moova_Helper_Data
     */
    protected $_helper;

    /**
     * @var array
     */
    protected $_token;

    /**
     * @var string
     */
    protected $_secretKey;

    /**
     * @var string
     */
    protected $_appId;

    /**
     * Improntus_Moova_Model_Webservice constructor.
     * @throws Mage_Core_Model_Store_Exception
     */
    public function __construct()
    {
        $this->_helper = Mage::helper('moova');

        $this->_user = $this->_helper->getWebserviceUser();
        $this->_pass = $this->_helper->getWebservicePass();
        $this->_apiUrl = $this->_helper->getApiUrl();
        $this->_secretKey = $this->_helper->getSecretKey();
        $this->_appId = $this->_helper->getAppId();
    }

    /**
     * @param $shippingParams
     * @return bool|null
     */
    public function getBudget($shippingParams)
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => "{$this->_apiUrl}b2b/v2/budgets?appId={$this->_appId}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($shippingParams),
                CURLOPT_HTTPHEADER => [
                    "Authorization: {$this->_secretKey}",
                    "Content-Type: application/json"
                ],
            ]
        );

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            $error = 'Se produjo un error al solicitar cotización: ' . curl_error($curl);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
            return false;
        }

        try {
            $cotizacion = json_decode($response, true);

            if (isset($cotizacion['status'])) {
                if ($cotizacion['code'] != 404) {
                    $error = 'Se produjo un error al solicitar cotización: ' . $cotizacion['message'];
                    Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
                }

                return false;
            } else {
                $this->_helper->setMoovaQuoteId($cotizacion['quote_id']);

                return $cotizacion['price'];
            }
        } catch (\Exception $e) {
            $error = 'Se produjo un error al solicitar cotización: ' . $e->getMessage() . ' Response: ' . print_r($response, true);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
            return null;
        }
    }

    /**
     * @param $shippingParams
     * @return bool|null
     */
    public function estimate($shippingParams)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => "{$this->_apiUrl}b2b/budgets/estimate?appId={$this->_appId}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($shippingParams),
                CURLOPT_HTTPHEADER => [
                    "Authorization: {$this->_secretKey}",
                    "Content-Type: application/json"
                ],
            ]
        );

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            $error = 'Se produjo un error al solicitar cotización: ' . curl_error($curl);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
            return false;
        }

        try {
            $cotizacion = json_decode($response, true);

            if (isset($cotizacion['status'])) {
                if ($cotizacion['code'] != 404) {
                    $error = 'Se produjo un error al solicitar cotización: ' . $cotizacion['message'];
                    Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
                }

                return false;
            } else {
                $this->_helper->setMoovaQuoteId($cotizacion['quote_id']);

                return $cotizacion['price'];
            }
        } catch (\Exception $e) {
            $error = 'Se produjo un error al solicitar cotización: ' . $e->getMessage() . ' Response: ' . print_r($response, true);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
            return null;
        }
    }

    /**
     * @param array $shippingParams
     * @return bool|mixed
     */
    public function newShipment($shippingParams)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => "{$this->_apiUrl}b2b/shippings?appId={$this->_appId}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($shippingParams),
                CURLOPT_HTTPHEADER => [
                    "Authorization: {$this->_secretKey}",
                    "Content-Type: application/json"
                ],
            ]
        );

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            $error = 'Se produjo un error al solicitar cotización: ' . curl_error($curl);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
            return false;
        }

        try {
            $shipment = json_decode($response, true);

            if (!isset($shipment['id']) && isset($shipment['errors'])) {
                $error = 'Se produjo un error al solicitar cotización. Response: ' . print_r($response, true);
                Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
                return false;
            }

            return $shipment;
        } catch (\Exception $e) {
            $error = 'Se produjo un error al solicitar cotización: ' . $e->getMessage() . ' Response: ' . print_r($response, true);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
            return false;
        }
    }

    /**
     * @param string $shipmentIdMoova
     * @return bool|mixed
     */
    public function getShipmentLabel($shipmentIdMoova)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => "{$this->_apiUrl}b2b/shippings/$shipmentIdMoova/label?appId={$this->_appId}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: {$this->_secretKey}",
                    "Content-Type: application/json"
                ],
            ]
        );

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            $error = 'Se produjo un error al solicitar cotización: ' . curl_error($curl);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
            return false;
        }

        try {
            $shipment = \Zend_Json::decode($response);

            return $shipment;
        } catch (\Exception $e) {
            $error = 'Se produjo un error al solicitar cotización: ' . $e->getMessage() . ' Response: ' . print_r($response, true);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);
            return false;
        }
    }

    /**
     * @param $order
     * @return mixed|null
     */
    public function getStatusFromUrlTracking($order)
    {
        $url = Mage::helper('shipping')->getTrackingPopupUrlBySalesModel($order);

        $query = parse_url($url, PHP_URL_QUERY);
        $queries = array();
        $shipmentId = null;
        parse_str($query, $queries);

        if (isset($queries['id'])) {
            $shipmentId = $queries['id'];
        }

        return $shipmentId;
    }

    /**
     * @param $shipmentId
     * @return bool|mixed
     */
    public function trackShipment($shipmentId)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => "{$this->_apiUrl}b2b/shippings/$shipmentId?appId={$this->_appId}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: {$this->_secretKey}",
                    "Content-Type: application/json"
                ],
            ]
        );

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            $error = 'Se produjo un error al solicitar cotización: ' . curl_error($curl);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);

            return false;
        }

        try {
            $shipment = \Zend_Json::decode($response);

            return $shipment;
        } catch (\Exception $e) {
            $error = 'Se produjo un error al solicitar cotización: ' . $e->getMessage() . ' Response: ' . print_r($response, true);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);

            return false;
        }
    }

    /**
     * @param $shipmentId
     * @param string $status
     * @return bool
     */
    public function sendStatusShipment($shipmentId, $status = 'READY')
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => "{$this->_apiUrl}b2b/shippings/$shipmentId/$status?appId={$this->_appId}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => [
                    "Authorization: {$this->_secretKey}",
                    "Content-Type: application/json"
                ],
            ]
        );

        $response = curl_exec($curl);

        $response_json = json_decode($response);

        if (curl_error($curl) || strtolower($response_json->status) == 'error') {
            $error = 'Se produjo un error al intentar enviar el estatus: ' . curl_error($curl);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);

            return false;
        }

        try {
            if (strtolower($response_json->status) == 'ready')
                return true;
            else
                return false;
        } catch (\Exception $e) {
            $error = 'Se produjo un error al intentar enviar el status: ' . $e->getMessage() . ' Response: ' . print_r($response, true);
            Mage::log($error, null, 'error_moova_' . date('m_Y') . '.log', true);

            return false;
        }
    }
}
