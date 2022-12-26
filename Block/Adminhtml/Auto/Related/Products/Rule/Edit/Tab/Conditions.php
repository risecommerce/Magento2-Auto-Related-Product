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
namespace Risecommerce\AutoRelatedProducts\Block\Adminhtml\Auto\Related\Products\Rule\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Conditions extends Generic implements TabInterface
{
    /**
     * @var \Magento\Backend\Block\Widget\Form\Renderer\Fieldset
     */
    protected $rendererFieldset;

    /**
     * @var \Magento\Rule\Block\Conditions|\Risecommerce\AutoRelatedProducts\Block\Conditions
     */
    protected $conditions;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * Conditions constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Risecommerce\AutoRelatedProducts\Block\Conditions $conditions
     * @param \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset
     * @param \Magento\Framework\App\RequestInterface $request
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Risecommerce\AutoRelatedProducts\Block\Conditions $conditions,
        \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset,
        \Magento\Framework\App\RequestInterface $request,
        array $data = []
    ) {
        $this->rendererFieldset = $rendererFieldset;
        $this->conditions = $conditions;
        $this->request = $request;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Where To Display');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Where To Display');
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
        if ($this->request->getParam('type') != 'category') {

            $renderer = $this->rendererFieldset->setTemplate(
                'Magento_CatalogRule::promo/fieldset.phtml'
            )->setNewChildUrl(
                $this->getUrl('risecommerce_auto_related_products/rules/newConditionHtml/form/rule_conditions_fieldset')
            );

            $fieldset = $form->addFieldset(
                'conditions_fieldset',
                [
                    'legend' => __(
                        'Apply the rule only if the following conditions are met (leave blank for all products).'
                    )
                ]
            )->setRenderer(
                $renderer
            );

            $fieldset->addField(
                'conditions',
                'text',
                ['name' => 'conditions', 'label' => __('Conditions'), 'title' => __('Conditions')]
            )->setRule(
                $model
            )->setRenderer(
                $this->conditions
            );
        } else {
            $fieldset = $form->addFieldset(
                'categories_fieldset',
                ['legend' => __('Apply the rule only for following categories (leave blank for all categories).')]
            );

            $fieldset->addField(
                'category_ids',
                \Risecommerce\AutoRelatedProducts\Block\Adminhtml\Chooser::class,
                [
                    'name' => 'category_ids',
                    'label' => __('Categories'),
                    'title' => __('Categories')
                ]
            );
        }
        $form->addValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
