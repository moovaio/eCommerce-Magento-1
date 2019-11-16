<?php

/**
 * Class Improntus_Moova_Helper_ShipmentSatus
 *
 * @author Improntus <http://www.improntus.com> - Ecommerce done right
 */
class Improntus_Moova_Helper_ShipmentStatus extends Mage_Core_Helper_Abstract
{
    /**
     * @var array
     */
    public static $shipmentMessage = [
        'DRAFT'     => 'El envío fue creado.',
        'READY'     => 'El envío se encuentra listo para ser procesado',
        'CONFIRMED' => 'Envío asignado a un Moover.',
        'PICKEDUP'  => 'Envío recogido por el Moover.',
        'INTRANSIT' => 'El envío está en viaje.',
        'DELIVERED' => 'Envío entregado satisfactoriamente.',
        'CANCELED'  => 'Envío cancelado por el usuario.',
        'INCIDENCE' => 'Incidencia inesperada.',
        'RETURNED'  => 'El envío fue devuelto a su lugar de origen.'
    ];

    /**
     * @param $code
     * @return mixed|null
     */
    public static function getShipmentMessage($code)
    {
        return isset(self::$shipmentMessage[$code]) ? __(self::$shipmentMessage[$code]) : null;
    }
}
