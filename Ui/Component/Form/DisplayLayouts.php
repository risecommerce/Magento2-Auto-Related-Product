<?php
/**
 * Class DisplayLayouts
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Ui\Component\Form;

/**
 * Class DisplayLayouts
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
class DisplayLayouts implements \Magento\Framework\Data\OptionSourceInterface
{
    const GRID = 0;
    const SLIDER = 1;

    /**
     * Return options for Display layouts
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::GRID, 'label' => __('Grid')],
            ['value' => self::SLIDER, 'label' => __('Slider')],
        ];
    }
}
