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
namespace Risecommerce\AutoRelatedProducts\Block\Adminhtml\Auto\Related\Products\Rule\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Actions extends Generic implements TabInterface
{
    /**
     * @var \Magento\Backend\Block\Widget\Form\Renderer\Fieldset
     */
    protected $rendererFieldset;

    /**
     * @var \Risecommerce\AutoRelatedProducts\Block\Actions
     */
    protected $ruleActions;

    /**
     * Actions constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Risecommerce\AutoRelatedProducts\Block\Actions $ruleActions
     * @param \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Risecommerce\AutoRelatedProducts\Block\Actions $ruleActions,
        \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset,
        array $data = []
    ) {
        $this->rendererFieldset = $rendererFieldset;
        $this->ruleActions = $ruleActions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Products To Show');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Products To Show');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering html
     *
     * @return Generic
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('Risecommerce_AutoRelatedProducts');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');

        $renderer = $this->rendererFieldset->setTemplate(
            'Magento_CatalogRule::promo/fieldset.phtml'
        )->setNewChildUrl(
            $this->getUrl('risecommerce_auto_related_products/rules/newActionHtml/form/rule_actions_fieldset')
        );

        $fieldset = $form->addFieldset(
            'actions_fieldset',
            [
                'legend' => __(
                    'Apply the rule only if the following conditions are met (leave blank for all products).'
                )
            ]
        )->setRenderer(
            $renderer
        );

        $fieldset->addField(
            'actions',
            'text',
            ['name' => 'actions', 'label' => __('Actions'), 'title' => __('Actions')]
        )->setRule(
            $model
        )->setRenderer(
            $this->ruleActions
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare form Html. call the phtml file with form.
     *
     * @return string
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        $html .= $this->setTemplate('Risecommerce_AutoRelatedProducts::risecommerce_auto_related_products_snippet_code.phtml')->toHtml();
        return $html;
    }

    /**
     * @return bool
     */
    public function getRuleId()
    {
        $model = $this->_coreRegistry->registry('Risecommerce_AutoRelatedProducts');
        if ($model->getRuleId()) {
            return $model->getRuleId();
        }
        return false;
    }
}
