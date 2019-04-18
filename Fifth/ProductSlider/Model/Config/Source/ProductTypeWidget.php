<?php

namespace Fifth\ProductSlider\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class ProductType
 * @package Mageplaza\Productslider\Model\Config\Source
 */
class ProductTypeWidget implements ArrayInterface
{
    const NEW_PRODUCTS         = 'new';
    const BEST_SELLER_PRODUCTS = 'best-seller';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->toArray() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    /**
     * @return array
     */
    protected function toArray()
    {
        return [
            self::NEW_PRODUCTS         => __('New Products'),
            self::BEST_SELLER_PRODUCTS => __('Best Seller Products'),
        ];
    }

    /**
     * @param $type
     * @return mixed|string
     */
    public function getLabel($type)
    {
        $types = $this->toArray();
        if (isset($types[$type])) {
            return $types[$type];
        }

        return '';
    }


}