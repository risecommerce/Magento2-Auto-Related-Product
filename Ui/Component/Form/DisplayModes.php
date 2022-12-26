<?php
/**
 * Class DisplayModes
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
 * Class DisplayModes
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
class DisplayModes implements \Magento\Framework\Data\OptionSourceInterface
{
    const BLOCK = 0;
    const AJAX = 1;

    /**
     * Return options for display modes
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::BLOCK, 'label' => __('Block')],
            ['value' => self::AJAX, 'label' => __('Ajax')],
        ];
    }
}
