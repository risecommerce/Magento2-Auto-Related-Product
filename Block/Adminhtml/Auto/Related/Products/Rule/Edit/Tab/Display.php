<?php
/**
 * Class Display
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

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Framework\App\RequestInterface;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\DisplayModes;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\DisplayLayouts;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\ProductOrder;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\AdditionalInfo;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\AddProducts;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\NotDisplayProducts;

class Display extends Generic implements TabInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var DisplayModes
     */
    protected $displayModes;

    /**
     * @var DisplayLayouts
     */
    protected $displayLayouts;

    /**
     * @var ProductOrder
     */
    protected $productOrder;

    /**
     * @var AdditionalInfo
     */
    protected $additionalInfo;

    /**
     * @var AddProducts
     */
    protected $addProducts;

    /**
     * @var NotDisplayProducts
     */
    protected $notDisplayProducts;

    /**
     * Display constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param RequestInterface $request
     * @param DisplayModes $displayModes
     * @param DisplayLayouts $displayLayouts
     * @param ProductOrder $productOrder
     * @param AdditionalInfo $additionalInfo
     * @param AddProducts $addProducts
     * @param NotDisplayProducts $notDisplayProducts
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        RequestInterface $request,
        DisplayModes $displayModes,
        DisplayLayouts $displayLayouts,
        ProductOrder $productOrder,
        AdditionalInfo $additionalInfo,
        AddProducts $addProducts,
        NotDisplayProducts $notDisplayProducts,
        array $data = []
    ) {
        $this->request = $request;
        $this->displayModes = $displayModes;
        $this->displayLayouts = $displayLayouts;
        $this->productOrder = $productOrder;
        $this->additionalInfo = $additionalInfo;
        $this->addProducts = $addProducts;
        $this->notDisplayProducts = $notDisplayProducts;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Display');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Display');
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

        $fieldset = $form->addFieldset('display_fieldset', ['legend' => __('Display Information')]);

        $fieldset->addField(
            'block_name',
            'text',
            [
                'name' => 'block_name',
                'label' => __('Block Name'),
                'title' => __('Block Name'),
                'required' => true,
                'note' => 'Enter title of the block.'
            ]
        );

        $fieldset->addField(
            'display_mode',
            'select',
            [
                'name' => 'display_mode',
                'label' => __('Display Mode'),
                'title' => __('Display Mode'),
                'values' => $this->displayModes->toOptionArray(),
            ]
        )->setAfterElementHtml(
            '
                <script>
                    require([
                         "jquery",
                    ], function($){
                        function disableMode() {
                            if($("#rule_position").val() == "custom"){
                                $("#rule_display_mode").val(0);
                                $("#rule_display_mode").prop("disabled", true);
                            }else{
                                $("#rule_display_mode").prop("disabled", false);
                            }
                            
                            if($("#rule_not_display_products").val() == "1" || $("#rule_not_display_products").val() == "2" || $("#rule_not_display_products").val() == "1,2" || $("#rule_not_display_products").val() == ",1,2"){
                                $("#rule_display_mode").val(1);
                                $("#rule_display_mode").prop("disabled", true);
                            }else{
                                $("#rule_display_mode").prop("disabled", false);
                            }
                        }
                        $(document).ready(function () {
                            disableMode();
                            $("#rule_position").change(function() {
                                disableMode();
                            })
                            
                            $("#rule_not_display_products").change(function() {
                                disableMode();
                            })
                        });
                      });
               </script>
            '
        );

        $fieldset->addField(
            'display_layout',
            'select',
            [
                'name' => 'display_layout',
                'label' => __('Display Layout'),
                'title' => __('Display Layout'),
                'values' => $this->displayLayouts->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'product_order',
            'select',
            [
                'name' => 'product_order',
                'label' => __('Product Sort Order'),
                'title' => __('Product Sort Order'),
                'values' => $this->productOrder->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'display_out_of_stock',
            'select',
            [
                'label' => __('Show Out Of Stock Products'),
                'title' => __('Show Out Of Stock Products'),
                'name' => 'display_out_of_stock',
                'default' => '0',
                'options' => ['1' => __('Yes'), '0' => __('No')]
            ]
        );

        $fieldset->addField(
            'no_of_products',
            'text',
            [
                'name' => 'no_of_products',
                'label' => __('Limit No. Of Products'),
                'title' => __('Limit No. Of Products'),
                'class' => 'validate-number validate-greater-than-zero',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'additional_info',
            'multiselect',
            [
                'name' => 'additional_info',
                'label' => __('Additional Information'),
                'title' => __('Additional Information'),
                'values' => $this->additionalInfo->toOptionArray(),
            ]
        );

        if ($this->request->getParam('type') == 'product') {
            $fieldset->addField(
                'add_products',
                'multiselect',
                [
                    'name' => 'add_products',
                    'label' => __('Add Products'),
                    'title' => __('Add Products'),
                    'values' => $this->addProducts->toOptionArray(),
                    'note' => 'Choose to also add Related/Cross-Sell/Up-Sell products of selected products in the block.'
                ]
            );
        }

        $fieldset->addField(
            'not_display_products',
            'multiselect',
            [
                'name' => 'not_display_products',
                'label' => __('Do Not Display Products'),
                'title' => __('Do Not Display Products'),
                'values' => $this->notDisplayProducts->toOptionArray(),
                'note' => 'Choose to do not show products in the block if it is added to the cart or wishlist.'
            ]
        );

        $form->setValues($model->getData());

        if ($model->isReadonly()) {
            foreach ($fieldset->getElements() as $element) {
                $element->setReadonly(true, true);
            }
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
