<?php
/**
 * Class Rules
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Block\Adminhtml;


class Rules extends \Magento\Backend\Block\Widget\Container
{
    /**
     * Prepare layout
     *
     * @return \Magento\Backend\Block\Widget\Container
     */
    protected function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'add_new_rule',
            'label' => __('Add Rule'),
            'class' => 'add',
            'button_class' => '',
            'class_name' => \Magento\Backend\Block\Widget\Button\SplitButton::class,
            'options' => $this->_getAddRulesButtonOptions(),
        ];
        $this->buttonList->remove('add');
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    /**
     * Retrieve options for 'Add Rule' split button
     *
     * @return array
     */
    protected function _getAddRulesButtonOptions()
    {
        $splitButtonOptions = [];
        $types = [
            ['value' => 'product', 'label' => 'Product'],
            ['value' => 'category', 'label' => 'Category'],
            ['value' => 'cart', 'label' => 'Cart'],
            ['value' => 'checkout', 'label' => 'Checkout'],
        ];
        foreach ($types as $type) {
            $splitButtonOptions[$type['value']] = [
                'label' => __($type['label']),
                'onclick' => "setLocation('" . $this->_getRulesCreateUrl($type['value']) . "')",
                'default' => $type['value'] == 'product',
            ];
        }

        return $splitButtonOptions;
    }

    /**
     * Retrieve rule create url by specified product type
     *
     * @param $type
     *
     * @return string
     */
    protected function _getRulesCreateUrl($type)
    {
        return $this->getUrl(
            'risecommerce_auto_related_products/*/new',
            ['type' => $type]
        );
    }
}
