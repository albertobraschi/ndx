<?php

/*
 * Run this over and over again until there are not more
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

$update = true;
$batchSize = 10000; // 0 for all you can just run it again, it only picks up categories where the anchor is 0
$update_msg = $update ? " Updated " :  " Not Updated ";

$startTime = time();

// Load Up Magento Core
// define('MAGENTO', realpath('/'));
// require_once(MAGENTO . 'app/Mage.php');

require_once '../app/Mage.php';
Mage::app();

$app = Mage::app();

$categories = Mage::getModel('catalog/category')
->getCollection()
->addAttributeToSelect('*')
->addAttributeToFilter('is_anchor', 0)
->addAttributeToFilter('entity_id', array("gt" => 1))
->setOrder('entity_id')
;

$count = 0;
foreach($categories as $category) {
    $count++;
    echo $category->getId() . " " . $category->getName();
    if ($update) {
        $category->setIsAnchor(1);
        $category->save();
    }
    echo " [$count] ($update_msg)  <br />" . PHP_EOL;
    if ($batchSize > 0 &&  $count >= $batchSize) {
        break;
    }
}
$endTime = time();
$elapsedTime = $endTime - $startTime;
echo "<br /> $count Categories [elapsedTime = $elapsedTime seconds]<br />". PHP_EOL. PHP_EOL;

