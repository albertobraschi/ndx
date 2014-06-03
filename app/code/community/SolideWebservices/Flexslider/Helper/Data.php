<?php
/**
 * @category	Solide Webservices
 * @package		Flexslider
 */

class SolideWebservices_Flexslider_Helper_Data extends Mage_Core_Helper_Abstract {

	/**
	 * Encode the mixed $valueToEncode into the JSON format
	 *
	 * @param mixed $valueToEncode
	 * @param  boolean $cycleCheck Optional; whether or not to check for object recursion; off by default
	 * @param  array $options Additional options used during encoding
	 * @return string
	 */
	public function jsonEncode($valueToEncode, $cycleCheck = false, $options = array()) {
		$json = Zend_Json::encode($valueToEncode, $cycleCheck, $options);
		/* @var $inline Mage_Core_Model_Translate_Inline */
		$inline = Mage::getSingleton('core/translate_inline');
		if ($inline->isAllowed()) {
			$inline->setIsJson(true);
			$inline->processResponseBody($json);
			$inline->setIsJson(false);
		}

		return $json;
	}

	/**
	 * Determine whether the extension is enabled
	 *
	 * @return bool
	 */
	public function isEnabled() {
		return Mage::getStoreConfig('flexslider/general/enabled');
	}

	/**
	 * Determine whether jQuery should be loaded
	 *
	 * @return bool
	 */
	public function isjQueryEnabled() {
		return Mage::getStoreConfig('flexslider/general/enable_jquery');
	}

	/**
	 * Determine which jQuery version should be loaded
	 *
	 * @return bool
	 */
	public function versionjQuery() {
		return Mage::getStoreConfig('flexslider/general/version_jquery');
	}

	/**
	 * return rgba based on hex color
	 *
	 * @print string
	 */
	function hex2rgb($hex){
		$hex = str_replace("#", "", $hex);
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
		print ''.$r.', '.$g.', '.$b.'';
	}
	
	/**
	 * return base url
	 *
	 * @print string
	 */
	function base_url(){
		return sprintf(
			"%s://%s",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			$_SERVER['HTTP_HOST']
		);
	}

}