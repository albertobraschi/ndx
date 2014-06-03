<?php

/**
 * Product:       Xtento_StockImport (2.1.3)
 * ID:            vh/sZbyj1YVVkVZ0OX8rIBnpbd+nYRbFlWAUD5Jv4Ec=
 * Packaged:      2014-05-01T15:26:35+00:00
 * Last Modified: 2013-06-26T18:02:10+02:00
 * File:          app/code/local/Xtento/StockImport/Model/System/Config/Source/Import/Startup.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

class Xtento_StockImport_Model_System_Config_Source_Import_Startup
{

    public function toOptionArray()
    {
        $pages = array();
        foreach (Mage::getBlockSingleton('xtento_stockimport/adminhtml_widget_menu')->getMenu() as $controllerName => $entryConfig) {
            if (!$entryConfig['is_link']) {
                continue;
            }
            $pages[] = array('value' => $controllerName, 'label' => Mage::helper('xtento_stockimport')->__($entryConfig['name']));
        }
        return $pages;
    }

}