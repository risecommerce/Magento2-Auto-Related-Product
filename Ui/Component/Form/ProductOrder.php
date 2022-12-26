<?php
/**
 * Class ProductOrder
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
 * Class ProductOrder
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
class ProductOrder implements \Magento\Framework\Data\OptionSourceInterface
{
    const BESTSELLER = 0;
    const LOW_TO_HIGH_PRICE = 1;
    const HIGH_TO_LOW_PRICE = 2;
    const NEW_ARRIVALS = 3;

    /**
     * Return options for product sort order
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::BESTSELLER, 'label' => __('BestSeller')],
            ['value' => self::LOW_TO_HIGH_PRICE, 'label' => __('Low To High Price')],
            ['value' => self::HIGH_TO_LOW_PRICE, 'label' => __('High To Low Price')],
            ['value' => self::NEW_ARRIVALS, 'label' => __('New Arrivals')],
        ];
    }
}
