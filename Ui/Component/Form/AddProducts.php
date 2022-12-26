<?php
/**
 * Class AddProducts
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
 * Class AddProducts
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
class AddProducts implements \Magento\Framework\Data\OptionSourceInterface
{
    const RELATED = 1;
    const UPSELL = 2;
    const CROSSSELL = 3;

    /**
     * Return options for add products
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('-- Please Select --')],
            ['value' => self::RELATED, 'label' => __('Related Products')],
            ['value' => self::CROSSSELL, 'label' => __('Cross-sell Products')],
            ['value' => self::UPSELL, 'label' => __('Up-sell Products')],
        ];
    }
}
