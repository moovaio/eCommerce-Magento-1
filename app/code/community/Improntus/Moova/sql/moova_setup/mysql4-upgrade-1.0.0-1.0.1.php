<?php
/** @var Mage_Eav_Model_Entity_Setup $setup */
$setup = $this;
$setup->startSetup();

$sales_quote_address = $setup->getTable('sales/quote_address');

$setup->getConnection()
    ->addColumn($sales_quote_address, 'altura', array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'comment' => 'Altura de calle'
    ));

$sales_order_address = $setup->getTable('sales/order_address');
$setup->getConnection()
    ->addColumn($sales_order_address, 'altura', array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'comment' => 'Altura de calle'
    ));

$setup->getConnection()
    ->addColumn($sales_quote_address, 'piso', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'Piso'
    ));

$sales_order_address = $setup->getTable('sales/order_address');
$setup->getConnection()
    ->addColumn($sales_order_address, 'piso', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'Piso'
    ));

$setup->getConnection()
    ->addColumn($sales_quote_address, 'departamento', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'Departamento'
    ));

$sales_order_address = $setup->getTable('sales/order_address');
$setup->getConnection()
    ->addColumn($sales_order_address, 'departamento', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'Departamento'
    ));

$setup->getConnection()
    ->addColumn($sales_quote_address, 'observaciones', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'Observaciones'
    ));

$sales_order_address = $setup->getTable('sales/order_address');
$setup->getConnection()
    ->addColumn($sales_order_address, 'observaciones', array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment' => 'Observaciones'
    ));

$sales_quote = $setup->getTable('sales/quote');

$setup->getConnection()
    ->addColumn($sales_quote, 'moova_quote_id', array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'comment' => 'Quote Id de cotizacion Moova'
    ));

$sales_order = $setup->getTable('sales/order');
$setup->getConnection()
    ->addColumn($sales_order, 'moova_quote_id', array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'comment' => 'Quote Id de cotizacion Moova'
    ));

$setup->endSetup();