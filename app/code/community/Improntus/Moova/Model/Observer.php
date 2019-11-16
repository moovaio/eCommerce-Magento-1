<?php

/**
 * Class Improntus_Moova_Model_Observer
 *
 * @author Improntus <http://www.improntus.com> - Ecommerce done right
 */
class Improntus_Moova_Model_Observer
{
    /**
     * @param $event
     */
    public function adminhtmlWidgetContainerHtmlBefore(Varien_Event_Observer $event)
    {
        $block = $event->getBlock();
        $order = Mage::registry('current_order');
        
        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View)
        {
            if(isset($order))
            {
                if($order->getShippingMethod() == 'moova_moova')
                {
                    if(!$order->hasShipments())
                    {
                        $urlSolicitar = Mage::helper("adminhtml")->getUrl('adminhtml/Moova/solicitar',array('order_id'=>$order->getId()));

                        $block->addButton(
                            'solicitar_Moova_button', array(
                                'label'     => 'Generar envío MOOVA',
                                'onclick'   => "confirmSetLocation('¿Esta seguro que desea generar el envío de sus productos?', '{$urlSolicitar}')",
                                'class'     => 'go',
                                'id'        => 'solicitar_moova'
                            )
                        );
                    }
                    else
                    {
                        $shippinStatusMoova = null;

                        if(count($order->getShipmentsCollection()->getItems()) == 1)
                        {
                            $shipment = reset($order->getShipmentsCollection()->getItems());
                            $shipmentId = count($shipment->getAllTracks() == 1) ? reset($shipment->getAllTracks())['number'] : null;

                            if($shipmentId)
                            {
                                $trackingInfo = Mage::getModel('moova/webservice')->trackShipment($shipmentId);
                                $shippinStatusMoova = isset($trackingInfo['status']) ? $trackingInfo['status'] : null;
                            }

                            if(isset($shippinStatusMoova))
                            {
                                $urlDescargar = Mage::helper("adminhtml")->getUrl('adminhtml/Moova/descargar',array('moova_id'=>$trackingInfo['id']));
                                $block->addButton(
                                    'descargar_etiqueta_moova', array(
                                        'label'     => 'Descargar etiqueta MOOVA',
                                        'class'     => 'success',
                                        'id'        => 'descargar_etiqueta_moova',
                                        'onclick'   => "setLocation('{$urlDescargar}')",
                                    )
                                );

                                if($shippinStatusMoova == 'DRAFT')
                                {
                                    $urlEnviar = Mage::helper("adminhtml")->getUrl('adminhtml/Moova/enviar',array('moova_id' =>$trackingInfo['id'], 'status' => 'READY'));

                                    $block->addButton(
                                        'listo_para_ser_entregado_moova', array(
                                            'label'     => 'Listo para ser entregado',
                                            'class'     => 'success',
                                            'id'        => 'listo_para_ser_entregado_moova',
                                            'onclick' => "setLocation('{$urlEnviar}')"
                                        )
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}