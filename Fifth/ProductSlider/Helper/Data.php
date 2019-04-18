<?php

namespace Fifth\ProductSlider\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    const XML_PATH_FIFTH_SLIDER = 'productslider/';

    public function getConfigValue($field, $storeId = null)
    {
        $value = $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
        switch ($value) {
            case 0 :
                  return  false;
                  break;
            case 1 :
                return  true;
                break;
            default:
                return  $value;
                break;
        }
    }
    /**
     * Retrieve all configuration options for product slider
     *
     * @return string
     * @throws \Zend_Serializer_Exception
     */
    public function getAllOptions()
    {
        $sliderOptions = '';
        $allConfig     = $this->getConfigValue(self::XML_PATH_FIFTH_SLIDER .'slider_design');
        foreach ($allConfig as $key => $value) {
            if ($key == 'item_slider') {
                $sliderOptions = $sliderOptions . $this->getResponseValue();
            } else if ($key != 'responsive') {
                if(in_array($key, ['loop', 'nav', 'dots', 'lazyLoad', 'autoplay', 'autoplayHoverPause'])){
                    $value = $value ? 'true' : 'false';
                }
                $sliderOptions = $sliderOptions . $key . ':' . $value . ',';
            }
        }

        return '{ ' . $sliderOptions . ' }';
    }
    public function isEnabled($storeId = null)
    {
        if ($this->getConfigValue(self::XML_PATH_FIFTH_SLIDER .'general/enabled' ,$storeId) == 1) {
            return true;
        }
        return false ;
    }



    public function  getDesignConfigByCode($code)
     {

        return $this->getConfigValue(self::XML_PATH_FIFTH_SLIDER .'slider_design/'.$code);
     }
}