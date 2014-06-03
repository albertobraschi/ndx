<?php

  class Smartwave_Zoom_Helper_Data extends Mage_Core_Helper_Abstract
  {
    /** Get Configuration Value**/ 
    public function getConfig($optionString){
        return Mage::getStoreConfig('zoom/'.$optionString);
    }
    
    /**
     * Check if module is enabled.
     * @return bool
     */
    public function isZoomEnabled()
    {
        return (bool) $this->getConfig('general/enable');
    }
    
    /**
     * Check if lightbox is enabled
     * @return bool
     */
    public function useLightbox()
    {
        return (bool) $this->getConfig('lightbox/lb_enable');
    }
    
    /**
     * Check if zoom is enabled
     * @return bool
     */
    public function useZoom()
    {
        if ($this->getConfig('general/used_zoom') && $this->getConfig('general/enable'))
            return true;
        else
            return false;
    }
    
    /**
     * Check if zoom position equals 'inner'
     * @return bool
     */
    public function isPositionInside()
    {
        return ($this->getConfig('general/type') == 'Inner');
    }
    
    
    /**
    * Get MainImage Options. If image width and height are not specified, return default value (200x200)
    */
    public function getMainImgOptions()
    {
        $imgOpt = array();
        $imgWidth = intval($this->getConfig('image/img_width'));
        $imgHeight = intval($this->getConfig('image/img_height'));
        $imgRate = $this->getConfig('image/img_rate');
        
        if ($imgWidth)
            $imgOpt['img_width'] = $imgWidth;
        else
            $imgOpt['img_width'] = 200;
        
        if ($imgRate)
            $imgOpt['img_height'] = $imgWidth * $imgRate;        
        elseif($imgHeight)
            $imgOpt['img_height'] = $imgHeight;
        else
            $imgOpt['img_height'] = 200;
            
        return $imgOpt;        
    }
    /**
    * Get Big Image Options. If Zoom Image Width and height are not specified, return default value.(600X600)
    */
    public function getBigImgOptions()
    {
        $imgOpt = array();
        if ($this->getConfig('general/used_zoom')) {
            $imgWidth = intval($this->getConfig('general/zoom_img_width'));
            $imgHeight = intval($this->getConfig('general/zoom_img_height'));
            if ($imgWidth)
                $imgOpt['img_width'] = $imgWidth;
            else
                $imgOpt['img_width'] = 600;
                
            if ($imgHeight)
                $imgOpt['img_height'] = $imgHeight;
            else
                $imgOpt['img_height'] = 600;
        } else {
            $imgOpt = $this->getMainImgOptions();
        }
        return $imgOpt;
    }
    
    /**
    * Get Light Box Image Size
    */
    public function getLBImgOptins()
    {
        $imgOpt = array();
        if ($this->getConfig('general/used_zoom')) {
            $imgOpt = $this->getBigImgOptions();
        } else {
            $mainImg = $this->getMainImgOptions();
            $imgOpt['img_width'] = $mainImg['img_width'] * 2;
            $imgOpt['img_height'] = $mainImg['img_height'] * 2;
        }
        return $imgOpt;
    }
    /**
    * Get Gallery Image Options.If Item Width and Height are not specified, return default value.(65X65)
    */
    public function getGalItemOptions()
    {
        $imgOpt = array();
        $imgWidth = intval($this->getConfig('gallery/ga_img_width'));
        $imgHeight = intval($this->getConfig('gallery/ga_img_height'));
        $imgNum = intval($this->getConfig('gallery/ga_item_num'));
        $imgBorder = intval($this->getConfig('gallery/ga_border_width'));
        $imgBorderCol = $this->getConfig('gallery/ga_border_color');
        $imgMargin = intval($this->getConfig('gallery/ga_item_margin'));
        
        if($imgWidth)
            $imgOpt['img_width'] = $imgWidth;
        else
            $imgOpt['img_width'] = 65;
            
        if($imgHeight)
            $imgOpt['img_height'] = $imgHeight;
        else
            $imgOpt['img_height'] = 65;
            
        if ($imgNum)
            $imgOpt['img_num'] = $imgNum;
        else
            $imgOpt['img_num'] = 4;
        
        if ($imgMargin)
            $imgOpt['img_margin'] = $imgMargin;
        else
            $imgOpt['img_margin'] = 5;
        
        if ($imgBorder)
            $imgOpt['img_border'] = $imgBorder;
        else
            $imgOpt['img_border'] = 0;
        
        if ($imgBorderCol)
            $imgOpt['img_border_color'] = $imgBorderCol;
        else
            $imgOpt['img_border_color'] = 0;
        
        
            
        return $imgOpt;
    }
    /* Get Loading Icon
     * @return string
     */
    public function getLoadingIcon() 
    {
        $iconName = $this->getConfig('general/loading_icon');
        if ($iconName)
            return Mage::getBaseUrl('media').'smartwave/catalog/product/view/media/'.$iconName;
        else
            return Mage::getBaseUrl('media').'smartwave/catalog/product/view/media/loading.gif';
    }
    /**
     * Get string with Image Zoom options
     * @return string
     */
    public function getZoomOptions()  
    {
        if ($this->getConfig('general/used_zoom')) {
            $cfg = array();
            
            $cfg[] = "responsive:true";
            
            $zoomType = $this->getConfig('general/type');
            $cfg[] = "zoomType:'$zoomType'";
            
            $scrollZoom = $this->getConfig('general/scroll_zoom');            
            $cfg[] = "scrollZoom:$scrollZoom";
            $loadingIcon = $this->getLoadingIcon();
            $cfg[] = "loadingIcon:'$loadingIcon'";            
            $easingActive = $this->getConfig('general/easing');
            $easingDuration = $this->getConfig('general/easing_duration');
            $cfg[] = "easing:$easingActive";            
            if ($easingActive && $easingDuration)
                $cfg[] = "easingDuration:$easingDuration";  
            switch($zoomType) {
                case 'inner':
                    $cfg[] = "cursor:'crosshair', zoomWindowFadeIn:500, zoomWindowFadeOut:500";
                    break;
                case 'window':
                    $cfg[] = "cursor: 'pointer'";
                    $zoomAreaBorderWidth = intval($this->getConfig('general/border_width'));
                    if ($zoomAreaBorderWidth) {
                        $cfg[] = "borderSize:$zoomAreaBorderWidth";
                        $borderColor = $this->getConfig('general/border_color');
                        if ($borderColor)
                            $cfg[] = "borderColour:'$borderColor'";
                    }
                    if (intval($this->getConfig('general/zoom_wind_width'))) {
                        $zoomWindWidth = intval($this->getConfig('general/zoom_wind_width')) + 2*intval($this->getConfig('image/img_border_width'));
                        $zoomWindHeight = intval($this->getConfig('general/zoom_wind_height')) + 2*intval($this->getConfig('image/img_border_width'));
                    } else {
                        $zoomWindWidth = 459 + 2*intval($this->getConfig('image/img_border_width'));
                        $zoomWindHeight = 459 + 2*intval($this->getConfig('image/img_border_width'));
                    }
                    $cfg[] = "zoomWindowWidth:$zoomWindWidth";
                    $cfg[] = "zoomWindowHeight:$zoomWindHeight"; 
                    $lensBorderWidth = intval($this->getConfig("general/lens_border_width"));
                    $lensBorderColor = $this->getConfig("general/lens_border_color");
                    if ($lensBorderWidth) {
                        $cfg[] = "lensBorderSize:$lensBorderWidth";
                        if ($lensBorderColor)
                            $cfg[] = "lensBorderColour:'$lensBorderColor'";                               
                    }
                    $tint = $this->getConfig("general/tint");
                    if($tint) {
                        $cfg[] = "tint:$tint";
                        $tintColor = $this->getConfig("general/tint_color");
                        if ($tintColor)
                            $cfg[] = "tintColour:'$tintColor'";
                        $tintOpacity = $this->getConfig("general/tint_opacity");
                        if ($tintOpacity)
                            $cfg[] = "tintOpacity:$tintOpacity";
                    }
                    $lensOpacity = $this->getConfig('general/lens_opacity');
                    if ($lensOpacity)
                        $cfg[] = "lensOpacity:$lensOpacity";
                    $lensColor = $this->getConfig('general/lens_color');
                    if ($lensColor)
                        $cfg[] = "lensColour:'$lensColor'";
                    break;
                case 'lens':
                    $cfg[] = "cursor: 'pointer'";
                    $zoomAreaBorderWidth = intval($this->getConfig('general/border_width'));
                    if ($zoomAreaBorderWidth) {
                        $cfg[] = "borderSize:$zoomAreaBorderWidth";
                        $borderColor = $this->getConfig('general/border_color');
                        if ($borderColor)
                            $cfg[] = "borderColour:'$borderColor'";
                    }
                    $lensShape = $this->getConfig('general/lens_shape');
                    $lensSize = $this->getConfig("general/lens_size");
                    $cfg[] = "lensShape:'$lensShape'";
                    if ($lensSize)
                        $cfg[] = "lensSize:$lensSize";                    
                    break;
                default:
                    $cfg[] = "cursor:'crosshair', zoomWindowFadeIn:500, zoomWindowFadeOut:500";
                    break;
            }
        } else {
            $cfg[] = "zoomEnabled:false";
        }
        $mainImgBorderSize = $this->getConfig('image/img_border_width');
        $mainImgBorderColor = $this->getConfig('image/img_border_color');
        if ($mainImgBorderSize) {
            $cfg[] = "imgBorderSize:$mainImgBorderSize";
            if ($mainImgBorderColor)
                $cfg[] = "imgBorderColour:'$mainImgBorderColor'";
        }
        $cfg[] = "imageCrossfade: true";
        return implode($cfg, ',');
    }
    /**
     * Get string with Gallery Item Style
     * @echo string
     */ 
    public function getItemStyle()
    {
        $imgBorder = intval($this->getConfig('gallery/ga_border_width'));        
        if ($imgBorder){
            echo '<style type="text/css">.gal-wrapper .slide {border: solid '.$imgBorder.'px '.$this->getConfig('gallery/ga_border_color').';} </style>';
        }
    }
    /**
     * Get string with Image Gallery options
     * @return string
     */
     public function getGalleryOptions()
     {
         $position = $this->getConfig('gallery/ga_position');
         $itemOpt = $this->getGalItemOptions();
         $item_width = $itemOpt['img_width'];
         $item_height = $itemOpt['img_height'];
         if ($position && ($position == 'left' || $position=='right')) {
             $mode = 'vertical';
         } else {
             $mode = 'horizontal';
         }
         
         $cfg = array();
         if ($item_width > 0) 
             $cfg[] = "slideWidth:$item_width";
         else 
             $cfg[] = "slideWidth:65";
         
         
         if ($item_height > 0)
            $cfg[] = "slideHeight:$item_height";
         else
            $cfg[] = "slideHeight:65";
         
         $item_num = $itemOpt['img_num'];
         if ($mode && ($mode == 'vertical')) {
             $cfg[] = "mode:'$mode'";
             $cfg[] = "minSlides:$item_num";
         } else {
             $cfg[] = "maxSlides: $item_num";
             $cfg[] = "minSlides: $item_num";
         }
         
         $item_margin = $itemOpt['img_margin'];
         $cfg[] = "slideMargin:$item_margin";
         $cfg[] = "moveSlides:1";
         $cfg[] = "pager:false";
         return implode($cfg, ',');
     }
     /**
     * Get string with Lightbox options
     * @return string
     */
     public function getLightBoxOptions()
     {
         if($this->getConfig('lightbox/lb_enable'))
         {
             $options = array();
             $options['position'] = $this->getConfig('lightbox/lb_icon_position');
             $options['icon'] = $this->getConfig('lightbox/zoom_icon');
             if ($options['icon'])
                 $options['icon'] = Mage::getBaseUrl('media').'smartwave/catalog/product/view/media/'.$options['icon'];
             else
                $options['icon'] = Mage::getBaseUrl('media').'smartwave/catalog/product/view/media/icon_search.png';
                
             if ($text = $this->getConfig('lightbox/zoom_text'))
                $options['zoom_text'] = $text;
             else
                $options['zoom_text'] = false;
             
             if ($font_color = $this->getConfig('lightbox/font_color'))
                $options['font_color'] = $font_color;
             else
                $options['font_color'] = '#000000';
             
             
             $font_size = intval($this->getConfig('lightbox/font_size'));
             if ($font_size)
                $options['font_size'] = $font_size;
             else 
                $options['font_size'] = 13;
             
             return $options;
         }
         return false;   
     }
  }
