<?php
/**
 * Class Position
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
 * Class Position
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
class Position implements \Magento\Framework\Data\OptionSourceInterface
{
    const REPLACE_RELATED = 'replace_related';
    const BEFORE_RELATED = 'before_related';
    const AFTER_RELATED = 'after_related';
    const REPLACE_UPSELL = 'replace_upsell';
    const BEFORE_UPSELL = 'before_upsell';
    const AFTER_UPSELL = 'after_upsell';
    const BEFORE_SIDEBAR = 'before_sidebar';
    const AFTER_SIDEBAR = 'after_sidebar';
    const REPLACE_CROSSSELL = 'replace_crosssell';
    const BEFORE_CROSSSELL = 'before_crosssell';
    const AFTER_CROSSSELL = 'after_crosssell';
    const BEFORE_CONTENT = 'before_content';
    const AFTER_CONTENT = 'after_content';
    const LEFT_POPUP = 'left_popup';
    const RIGHT_POPUP = 'right_popup';
    const CUSTOM = 'custom';

    /**
     * RequestInterface
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * Options
     *
     * @var array
     */
    public $options;

    /**
     * Position constructor.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * Return options for rule position
     *
     * @return array
     */
    public function toOptionArray()
    {
        $this->options = [];
        if ($this->request->getParam('type') == 'product') {
            $this->options[] = ['value' => self::REPLACE_RELATED, 'label' => 'Replace Related Products'];
            $this->options[] = ['value' => self::BEFORE_RELATED, 'label' => 'Before Related Products'];
            $this->options[] = ['value' => self::AFTER_RELATED, 'label' => 'After Related Products'];
            $this->options[] = ['value' => self::REPLACE_UPSELL, 'label' => 'Replace Upsell Products'];
            $this->options[] = ['value' => self::BEFORE_UPSELL, 'label' => 'Before Upsell Products'];
            $this->options[] = ['value' => self::AFTER_UPSELL, 'label' => 'After Upsell Products'];
        } elseif ($this->request->getParam('type') == 'category') {
            $this->options[] = ['value' => self::BEFORE_SIDEBAR, 'label' => 'Before Sidebar'];
            $this->options[] = ['value' => self::AFTER_SIDEBAR, 'label' => 'After Sidebar'];
        } elseif ($this->request->getParam('type') == 'cart') {
            $this->options[] = ['value' => self::REPLACE_CROSSSELL, 'label' => 'Replace Cross-sell Products'];
            $this->options[] = ['value' => self::BEFORE_CROSSSELL, 'label' => 'Before Cross-sell Products'];
            $this->options[] = ['value' => self::AFTER_CROSSSELL, 'label' => 'After Cross-sell Products'];
        }

        $this->options[] = ['value' => self::BEFORE_CONTENT, 'label' => 'Before Content'];
        $this->options[] = ['value' => self::AFTER_CONTENT, 'label' => 'After Content'];
        $this->options[] = ['value' => self::LEFT_POPUP, 'label' => 'Left Popup'];
        $this->options[] = ['value' => self::RIGHT_POPUP, 'label' => 'Right Popup'];
        $this->options[] = ['value' => self::CUSTOM, 'label' => 'Manually'];

        return $this->options;
    }
}
