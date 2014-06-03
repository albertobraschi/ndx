<?php

/*
 * Runs for first 10000 categories only
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo basename(__FILE__) . " version 1.1 <br />" . PHP_EOL;
echo "format: " . basename(__FILE__). "?storeid=nnn     (storeid is optional)<br />" . PHP_EOL;

$update = true;
$batchSize = 10000; // 0 for all you can just run it again, it only picks up categories where the anchor is 0
$update_msg = $update ? " Updated " :  " Not Updated ";

$startTime = time();

require_once '../app/Mage.php';
$app = Mage::app();

$store_id = '1';
if (!empty($_GET['storeid'])) {
    $store_id = $_GET['storeid'];
}


Mage::app()->setCurrentStore($store_id);
$currentStore = Mage::app()->getStore()->getName();
$currentStoreId = Mage::app()->getStore()->getId();
echo "Using = $currentStore (storeId=$currentStoreId)<br />" . PHP_EOL;
echo " <br />" . PHP_EOL;

$categories = Mage::getModel('catalog/category')
    ->getCollection()
    ->addAttributeToSelect('*')
//    ->addAttributeToFilter('is_anchor', 0)
//    ->addAttributeToFilter('is_anchor', array("neq" => 1))  // does not return when null - ie Not in DB at all
    ->addAttributeToFilter('entity_id', array("gt" => 1))
    ->setOrder('entity_id')
;

$catCount = count($categories);

echo "there are $catCount categories <br />" . PHP_EOL;

$count = 0;
foreach($categories as $category) {

    $anchor = $category->getIsAnchor();

    $mess1 = $category->getId() . " " . $category->getName() . " (anchor=$anchor) ";

    if ($anchor == 1 ) {
        continue;
    }

    $count++;
    echo $count . '/'. $mess1;

    if ($update) {
        // setAnchor and setData both work

        //$category->setIsAnchor(1);
        $category->setData('is_anchor', 1);
        $return = $category->save();

        $anchorNew = $category->getIsAnchor();

    }
    echo "  ($update_msg) (new anchor=$anchorNew)<br />" . PHP_EOL;

    if ($batchSize > 0 &&  $count >= $batchSize) {
        break;
    }
}

$endTime = time();
$elapsedTime = $endTime - $startTime;
echo "<br /> $count Categories with Anchor not set [elapsedTime = $elapsedTime seconds]<br />". PHP_EOL. PHP_EOL;

