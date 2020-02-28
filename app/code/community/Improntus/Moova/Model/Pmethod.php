<?php

class Improntus_Moova_Model_Pmethod
{
    public function toOptionArray()
    {
        /*
        $order = Mage::getModel('sales/order')->load(2);
        $order = $order->getShippingAddress()->getData();
        throw new Exception(json_encode($order));
        */
        $res    = Mage::getSingleton('core/resource');
        $read   = $res->getConnection('core_read');
        $table  = $res->getTableName('sales/order_address');
        $headers = array_keys($read->describeTable($table));
        
        $response = [
            [
                'value'=>'',
                'label'=>'N/A'
            ]
           
            ];
            
        foreach($headers as $header){
            $response[]=[
                'value' => $header,
                'label' => $header,
            ];
        }
        
        return $response;
    }
}
 