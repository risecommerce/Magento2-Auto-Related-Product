<?php
/**
 * Class AdditionalInfo
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
 * Class AdditionalInfo
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
class AdditionalInfo implements \Magento\Framework\Data\OptionSourceInterface
{
    const PRICE = 1;
    const ADD_TO_CART = 2;
    const ADD_TO_WISHLIST = 3;
    const ADD_TO_COMPARE = 4;
    const REVIEW = 5;

    /**
     * Return options for additional info
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('-- Please Select --')],
            ['value' => self::PRICE, 'label' => __('Price')],
            ['value' => self::ADD_TO_CART, 'label' => __('Add to Cart')],
            ['value' => self::ADD_TO_WISHLIST, 'label' => __('Add to Wishlist')],
            ['value' => self::ADD_TO_COMPARE, 'label' => __('Add to Compare')],
            ['value' => self::REVIEW, 'label' => __('Review Information')],
        ];
    }
}
