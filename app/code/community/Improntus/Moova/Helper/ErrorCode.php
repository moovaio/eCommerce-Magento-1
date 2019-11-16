<?php

/**
 * Class Improntus_Moova_Helper_ErrorCode
 *
 * @author Improntus <http://www.improntus.com> - Ecommerce done right
 */
class Improntus_Moova_Helper_ErrorCode extends Mage_Core_Helper_Abstract
{
    /**
     * @var array
     */
    public static $errorCode = [
        '100' => 'Non-existent shipment.',
        '101' => 'Shipping Canceled.',
        '102' => 'Shipping Closed.',
        '104' => 'There is no Carrier to make the Shipment.',
        '200' => 'Non-existent User.',
        '300' => 'Suspended carrier.',
        '301' => 'Suspended carrier.',
        '400' => 'Non-existent carrier medium.',
        '401' => 'Unauthorized -- Your API key is wrong.',
        '403' => 'Forbidden -- The request is forbidden for your access level, contact the developer support.',
        '404' => 'Not Found -- The request is not longer active, or does not exist.',
        '405' => 'Method Not Allowed -- You tried to access a request with an invalid method.',
        '406' => 'Not Acceptable -- You requested a format that isn\'t JSON.',
        '410' => 'Gone -- The endpoint requested has been removed from our servers.',
        '429' => 'Too Many Requests -- You\'re requesting too much information! Slow down!',
        '500' => 'MercadoPago Error.',
        '503' => 'Service Unavailable -- We\'re temporarily offline for maintenance. Please try again later.',
        '600' => 'The package is too big.',
        '601' => 'Wrong budget params.',
        '602' => 'Promo Code does not exist.',
        '603' => 'Transport does not exist.',
        '604' => 'No available riders using this method.',
        '605' => 'Location does not exist.',
        '606' => 'Element pays must have `PAGA_RECEPTOR` or  `PAGA_EMISOR`.',
        '607' => 'Wrong email/password.',
        '608' => 'User already exists.',
        '609' => 'Incomplete params.',
        '610' => 'Error Starting shipping.',
        '611' => 'Error Cancelling shipping.',
        '612' => 'Year must be 4 digits long.',
        '613' => 'The Card is invalid.',
        '614' => 'Error adding Credit Card to the user.',
        '615' => 'User already exists.',
        '616' => 'Invalid/Expired token',
        '617' => 'Error to find history.'
    ];

    /**
     * @param $code
     * @return mixed|null
     */
    public static function getError($code)
    {
        return isset(self::$errorCode[$code]) ? self::$errorCode[$code] : null;
    }
}
