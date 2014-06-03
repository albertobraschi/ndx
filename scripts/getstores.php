<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo basename(__FILE__) . " version 1.0 <br />" . PHP_EOL;

require_once '../app/Mage.php';
$app = Mage::app();

$defaultStore = Mage::app()->getStore()->getName();
echo "defaultStore = $defaultStore <br />" . PHP_EOL;
echo " <br />" . PHP_EOL;

echo "All Stores<br />" . PHP_EOL;
$allStores = Mage::app()->getStores();
foreach ($allStores as $_eachStoreId => $val)
{
    $_storeCode = Mage::app()->getStore($_eachStoreId)->getCode();
    $_storeName = Mage::app()->getStore($_eachStoreId)->getName();
    $_storeId = Mage::app()->getStore($_eachStoreId)->getId();
    echo "storeId = $_storeId <br />" . PHP_EOL;
    echo "storeCode = $_storeCode <br />" . PHP_EOL;
    echo "storeName = $_storeName <br />" . PHP_EOL;
    echo " <br />" . PHP_EOL;
}

exit;


$store_id = '3';
$mageRunCode = 'chef_newdesign';
$mageRunType = 'store';

Mage::app()->setCurrentStore($store_id);
$currentStore = Mage::app()->getStore()->getName();
echo "currentStore = $currentStore <br />" . PHP_EOL;
echo " <br />" . PHP_EOL;

// open the store
//Mage::run($mageRunCode, $mageRunType);
/*

getstores.php version 1.0
defaultStore Default Store View

storeId 3
storeCode chef_newdesign
storeName Chef Version
storeDd 1
storeCode default
storeName Default Store View


*/