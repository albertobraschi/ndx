<?php
// Gallery Position

class Smartwave_Zoom_Model_System_Config_Source_Gallery_Position
{
    public function toOptionArray()
    {
        return array(            
            array('value' => 'bottom',        'label' => Mage::helper('smartwave_zoom')->__('Bottom')),
            array('value' => 'left',        'label' => Mage::helper('smartwave_zoom')->__('Left')),
            array('value' => 'right',        'label' => Mage::helper('smartwave_zoom')->__('Right')),
            array('value' => 'top',            'label' => Mage::helper('smartwave_zoom')->__('Top'))            
        );
    }
}