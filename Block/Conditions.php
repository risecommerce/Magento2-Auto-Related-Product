<?php
/**
 * Class Conditions
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

class Conditions implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Conditions constructor.
     *
     * @param \Magento\Backend\Model\Url $backendUrlManager
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Backend\Model\Url $backendUrlManager,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->backendUrlManager = $backendUrlManager;
        $this->request = $request;
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
        if ($element->getRule() && $element->getRule()->getConditions()) {
            $html = $element->getRule()->getConditions()->asHtmlRecursive();
            if ($this->request->getParam('type') == 'product') {
                $url = $this->backendUrlManager->getUrl('risecommerce_auto_related_products/preview/conditionproducts');
                $html .= '<button
                type="button"
                id="risecommerce_auto_related_products_conditions_preview_product_button"
                class="risecommerce-auto-related-products-conditions-preview-product-button"
                data-mage-init=\'{"risecommerce-auto-related-preview-products":{"url": "'.$url.'", "type": "conditions"}}\'
                data-hidelist="false">Preview Products</button>
                <div class="risecommerce-auto-related-products-conditions-product-list"></div>';
            }
            return $html;
        }
        return '';
    }
}
