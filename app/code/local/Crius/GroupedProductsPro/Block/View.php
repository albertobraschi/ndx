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
/**
 * Grouped product view block
 */
class Crius_GroupedProductsPro_Block_View extends Mage_Catalog_Block_Product_View_Type_Grouped
{
    /**
     * Array of attributes used for the table
     *
     * @var array
     */
    protected $_tableAttributes = null;
    
    /**
     * Array of tier prices grouped by quantity
     *
     * @var array
     */
    protected $_groupedTierPrices = null;
    
    /**
     * Default template for a single tier price
     *
     * @var array
     */
    protected $_singleTierPriceDefaultTemplate  = 'groupedproductspro/tierprice.phtml';
    
    /**
     * Get attributes for the product table
     *
     * @return array
     */
    public function getTableAttributes()
    {
        if (!$this->_tableAttributes) {
            $this->_tableAttributes = array();
            // Collect relevant attributes from products
            foreach ($this->getAssociatedProducts() as $item) {
                foreach ($item->getAttributes() as $attribute) {
                    // Add attribute to table if it is an allowed attribute and if product has attribute data
                    if ($attribute->getIsVisibleInGroupedTable() && $item->getData($attribute->getAttributeCode()) && ($attribute->getFrontendInput() != 'media_image' || $item->getData($attribute->getAttributeCode()) != 'no_selection')) {
                        // Use attribute code as key to avoid duplicates
                        $this->_tableAttributes[$attribute->getAttributeCode()] = $attribute;
                    }
                }
            }
            // Remove keys and sort attributes
            $this->_tableAttributes = array_values($this->_tableAttributes);
			usort($this->_tableAttributes, array('Crius_GroupedProductsPro_Block_View', 'compareAttributes'));
        }
        return $this->_tableAttributes;
    }
    
    /**
     * Comparison function for sorting attributes
     *
     * @param $attr1 First attribute
     * @param $attr2 Second attribute
     * @return int
     */
    public static function compareAttributes($attr1, $attr2)
    {
        if ($attr1->getGroupedTableSortOrder() == $attr2->getGroupedTableSortOrder()) {
            return 0;
        }
        return ($attr1->getGroupedTableSortOrder() > $attr2->getGroupedTableSortOrder()) ? 1 : -1;
    }
    
    /**
     * Get all tier prices grouped and sorted by quantity
     *
     * @return array
     */
    public function getTierPricesGroupedByQuantity()
    {
        if (!$this->_groupedTierPrices) {
            $this->_groupedTierPrices = array();
            // Go through all products
            foreach ($this->getAssociatedProducts() as $item) {
                // Create price block and collect tier prices
                $priceBlock = $this->_getTierPriceBlock($item);
                foreach ($priceBlock->getTierPrices($item) as $price) {
                    // Add quantity as key in the array if not already created
                    $qty = $price['price_qty'];
                    if (!isset($this->_groupedTierPrices[$qty])) {
                        $this->_groupedTierPrices[$qty] = array();
                    }
                    // Add tier price to array of tier prices for this quantity
                    $this->_groupedTierPrices[$qty][$item->getId()] = $price;
                }
            }
            // Sort by quantity (low to high)
            ksort($this->_groupedTierPrices);
        }
        return $this->_groupedTierPrices;
    }
    
    /**
     * Get tier price HTML for a given product and quantity
     *
     * @return string
     */
    public function getTierPriceHtmlForQuantity($item, $qty)
    {
        $prices = $this->getTierPricesGroupedByQuantity();
        if (isset($prices[$qty]) && isset($prices[$qty][$item->getId()])) {
            $price = $prices[$qty][$item->getId()];
            return $this->getLayout()->createBlock('core/template')
                ->setTemplate($this->getSingleTierPriceTemplate())
                ->setProduct($item)
                ->setTierPrice($price)
                ->toHtml();
        }
        return '';
    }
    
    /**
     * Template file name for single tier price
     *
     * @return string
     */
    public function getSingleTierPriceTemplate()
    {
        if (!$this->hasData('single_tier_price_template')) {
            return $this->_singleTierPriceDefaultTemplate;
        }
        return $this->getData('single_tier_price_template');
    }
    
    /**
     * Get the price block used for tier price calculations
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _getTierPriceBlock($product = null)
    {
        if (is_null($product)) {
            $product = $this->getProduct();
        }
        return $this->_getPriceBlock($product->getTypeId())
            ->setTemplate($this->getTierPriceTemplate())
            ->setProduct($product)
            ->setInGrouped($this->getProduct()->isGrouped());
    }
    
    /**
     * Show add to cart buttons for each row?
     *
     * @return boolean
     */
    public function showAddToCartForRows()
    {
        return $this->_getConfigValue('add_row_to_cart');
    }
    
    /**
     * Show add to cart button in footer?
     *
     * @return boolean
     */
    public function showAddToCartInFooter()
    {
        return $this->_getConfigValue('add_all_to_cart');
    }
    
    /**
     * Show quantity fields in rows?
     *
     * @return boolean
     */
    public function showQuantityFields()
    {
        return $this->_getConfigValue('show_quantity');
    }
    
    /**
     * Show separate columns for tier prices (as opposed to all tier price text in the price column)
     *
     * @return boolean
     */
    public function showSeparateTierPrices()
    {
        return $this->_getConfigValue('separate_tier_prices');
    }
    
    /**
     * Get image width
     *
     * @return int
     */
    public function getImageWidth()
    {
        return Mage::getStoreConfig('groupedproductspro/settings/image_width');
    }
    
    /**
     * Get image height
     *
     * @return int
     */
    public function getImageHeight()
    {
        return Mage::getStoreConfig('groupedproductspro/settings/image_height');
    }
    
    /**
     * Get configuration value for key. First priority = product settings, second priority = system configuration
     *
     * @param string $key
     * @return mixed
     */
    protected function _getConfigValue($key)
    {
        // Return general configuration value if product value is not set
        $configvalue = Mage::getStoreConfig('groupedproductspro/settings/'.$key);
        $productconfigvalue = $this->getProduct()->getData('gpp_'.$key);
        switch ($productconfigvalue) {
            case 1:
                return true;
            case 2:
                return false;
            default:
                return $configvalue;
        }
    }
}