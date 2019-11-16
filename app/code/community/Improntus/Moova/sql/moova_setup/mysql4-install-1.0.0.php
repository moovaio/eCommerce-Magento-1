<?php
/** @var Mage_Eav_Model_Entity_Setup $setup */
$setup = $this;
$setup->startSetup();

$entityTypeId = $setup->getEntityTypeId('customer_address');
$attributeSetId = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$usedInForms = array(
    'adminhtml_customer_address',
    'customer_address_edit',
    'customer_register_address'
);

$setup->addAttribute('customer_address', 'altura', array(
    'type'      => 'int',
    'backend'   => '',
    'label'     => 'Altura',
    'input'     => 'text',
    'source'    => '',
    'visible'   => true,
    'required'  => true,
    'default'   => '',
    'frontend'  => '',
    'unique'    => false,
    'note'      => 'Altura de calle'
    )
);

$eavConfig = Mage::getSingleton('eav/config');

$store = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$attribute = $eavConfig->getAttribute('customer_address', 'altura');
$attribute->setWebsite($store->getWebsite());
$attribute->setData('used_in_forms', $usedInForms);
$attribute->save();

$setup->addAttribute('customer_address', 'piso', array(
        'type'      => 'varchar',
        'backend'   => '',
        'label'     => 'Piso',
        'input'     => 'text',
        'source'    => '',
        'visible'   => true,
        'required'  => false,
        'default'   => '',
        'frontend'  => '',
        'unique'    => false,
        'note'      => 'Piso'
    )
);

$eavConfig = Mage::getSingleton('eav/config');

$store = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$attribute = $eavConfig->getAttribute('customer_address', 'piso');
$attribute->setWebsite($store->getWebsite());
$attribute->setData('used_in_forms', $usedInForms);
$attribute->save();

$setup->addAttribute('customer_address', 'departamento', array(
        'type'      => 'varchar',
        'backend'   => '',
        'label'     => 'Departamento',
        'input'     => 'text',
        'source'    => '',
        'visible'   => true,
        'required'  => false,
        'default'   => '',
        'frontend'  => '',
        'unique'    => false,
        'note'      => 'Departamento'
    )
);

$eavConfig = Mage::getSingleton('eav/config');

$store = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$attribute = $eavConfig->getAttribute('customer_address', 'departamento');
$attribute->setWebsite($store->getWebsite());
$attribute->setData('used_in_forms', $usedInForms);
$attribute->save();

$setup->addAttribute('customer_address', 'observaciones', array(
    'type'      => 'varchar',
    'backend'   => '',
    'label'     => 'Observaciones',
    'input'     => 'text',
    'source'    => '',
    'visible'   => true,
    'required'  => false,
    'default'   => '',
    'frontend'  => '',
    'unique'    => false,
    'note'      => 'Observaciones'
    )
);

$eavConfig = Mage::getSingleton('eav/config');

$store = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$attribute = $eavConfig->getAttribute('customer_address', 'observaciones');
$attribute->setWebsite($store->getWebsite());
$attribute->setData('used_in_forms', $usedInForms);
$attribute->save();

$setup->endSetup();