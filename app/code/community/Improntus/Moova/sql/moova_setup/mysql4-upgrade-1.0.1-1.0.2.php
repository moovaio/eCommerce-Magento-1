<?php
/** @var Mage_Eav_Model_Entity_Setup $setup */
$setup = $this;
$setup->startSetup();

$setup->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'alto',
    array(
        'frontend'      => '',
        'label'         => 'Alto',
        'input'         => 'text',
        'class'         => '',
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'       => true,
        'required'      => true,
        'user_defined'  => false,
        'default'       => '',
        'group'         => 'General',
        'type'          => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'backend'       => '',
        'source'        => '',
        'searchable'    => false,
        'filterable'    => false,
        'unique'        => false,
        'comparable'    => false,
        'visible_on_front'        => false,
        'is_used_in_grid'         => false,
        'is_visible_in_grid'      => false,
        'is_filterable_in_grid'   => false,
        'used_in_product_listing' => true,
    )
);

$setup->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'largo',
    array(
        'frontend'      => '',
        'label'         => 'Largo',
        'input'         => 'text',
        'class'         => '',
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'       => true,
        'required'      => true,
        'user_defined'  => false,
        'default'       => '',
        'group'         => 'General',
        'type'          => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'backend'       => '',
        'source'        => '',
        'searchable'    => false,
        'filterable'    => false,
        'unique'        => false,
        'comparable'    => false,
        'visible_on_front'        => false,
        'is_used_in_grid'         => false,
        'is_visible_in_grid'      => false,
        'is_filterable_in_grid'   => false,
        'used_in_product_listing' => true,
    )
);

$setup->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ancho',
    array(
        'frontend'      => '',
        'label'         => 'Ancho',
        'input'         => 'text',
        'class'         => '',
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'       => true,
        'required'      => true,
        'user_defined'  => false,
        'default'       => '',
        'group'         => 'General',
        'type'          => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'backend'       => '',
        'source'        => '',
        'searchable'    => false,
        'filterable'    => false,
        'unique'        => false,
        'comparable'    => false,
        'visible_on_front'        => false,
        'is_used_in_grid'         => false,
        'is_visible_in_grid'      => false,
        'is_filterable_in_grid'   => false,
        'used_in_product_listing' => true,
    )
);
$setup->endSetup();