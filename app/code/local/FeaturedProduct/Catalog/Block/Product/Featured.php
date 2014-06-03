<?php

class FeaturedProduct_Catalog_Block_Product_Featured extends Mage_Catalog_Block_Product_Abstract
{
    public function getFeaturedProducts()
    {
        //database connection object
        $storeId = Mage::app()->getStore()->getId();

        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('featured');
        $collection->addFieldToFilter(array(
            array('attribute' => 'featured', 'eq' => true),
        ));

        return $collection;
    }
}