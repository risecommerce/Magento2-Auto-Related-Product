<?php
/**
 * Class Actions
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Block;

use Magento\Framework\Data\Form\Element\AbstractElement;


class Actions implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Actions constructor.
     *
     * @param \Magento\Backend\Model\Url $backendUrlManager
     */
    public function __construct(
        \Magento\Backend\Model\Url $backendUrlManager
    ) {
        $this->backendUrlManager = $backendUrlManager;
    }

    /**
     * Render element
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        if ($element->getRule() && $element->getRule()->getActions()) {
            $html = $element->getRule()->getActions()->asHtmlRecursive();
            $url = $this->backendUrlManager->getUrl('risecommerce_auto_related_products/preview/actionproducts');
                $html .= '<button
                type="button"
                id="risecommerce_auto_related_products_actions_preview_product_button"
                class="risecommerce-auto-related-products-actions-preview-product-button"
                data-mage-init=\'{"risecommerce-auto-related-preview-products":{"url": "'.$url.'", "type": "actions"}}\'
                data-hidelist="false">Preview Products</button>
                <div class="risecommerce-auto-related-products-actions-product-list"></div>';
            return $html;
        }
        return '';
    }
}
