<?php
/**
 * Crius
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt
 *
 * @category   Crius
 * @package    Crius_GroupedProductsPro
 * @copyright  Copyright (c) 2012 Crius (http://www.criuscommerce.com)
 * @license    http://www.criuscommerce.com/CRIUS-LICENSE.txt
 */
 
$installer = $this;

$helper = Mage::helper('groupedproductspro');

$installer->startSetup();

$installer->addAttribute('catalog_product', 'gpp_separate_tier_prices', array(
    'group'             => 'Table Settings',
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => $helper->__('Tier Price Columns'),
    'input'             => 'select',
    'class'             => '',
    'source'            => 'groupedproductspro/product_attribute_source_enabledisableconfig',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => 'grouped',
    'is_configurable'   => false,
));

$installer->endSetup();
