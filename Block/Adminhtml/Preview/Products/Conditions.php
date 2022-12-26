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
namespace Risecommerce\AutoRelatedProducts\Block\Adminhtml\Preview\Products;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;

class Conditions extends Extended
{
    /**
     * @var \Risecommerce\AutoRelatedProducts\Model\Product\AttributeSet\Options
     */
    protected $attributeSet;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $productType;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $productStatus;

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $localeCurrency;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $productVisibility;

    /**
     * @var ProductCollection
     */
    protected $productFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $collection;

    /**
     * Conditions constructor.
     *
     * @param Context $context
     * @param Data $backendHelper
     * @param ProductCollection $productFactory
     * @param \Risecommerce\AutoRelatedProducts\Model\Product\AttributeSet\Options $attributeSet
     * @param \Magento\Catalog\Model\Product\Type $productType
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     * @param \Magento\Framework\Locale\CurrencyInterface $localeCurrency
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        ProductCollection $productFactory,
        \Risecommerce\AutoRelatedProducts\Model\Product\AttributeSet\Options $attributeSet,
        \Magento\Catalog\Model\Product\Type $productType,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        array $data = []
    ) {
        $this->attributeSet = $attributeSet;
        $this->productType = $productType;
        $this->productStatus = $productStatus;
        $this->localeCurrency = $localeCurrency;
        $this->storeManager = $storeManager;
        $this->productVisibility = $productVisibility;
        $this->productFactory = $productFactory;
        $this->collection = $this->productFactory->create();
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Initialize block
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('conditions_index');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection
     *
     * @return Extended
     */
    protected function _prepareCollection()
    {
        if ($this->getData('product_ids')) {
            $this->collection
                ->addFieldToFilter('entity_id', ['in' => $this->getData('product_ids')])
                ->addAttributeToSelect('*');
        }
        $this->collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
        $this->collection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $this->setCollection($this->collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare Columns
     *
     * @return $this|Extended
     *
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->addColumn(
            'preview_entity_id',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'entity_id',
                'align' => 'center',
                'index' => 'entity_id',
            ]
        );
        $this->addColumn(
            'preview_entity_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'preview_name',
            [
                'header' => __('Name'),
                'type' => 'text',
                'index' => 'name',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'preview_sku',
            [
                'header' => __('Sku'),
                'type' => 'text',
                'index' => 'sku',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'preview_type_id',
            [
                'header' => __('Type'),
                'type' => 'options',
                'index' => 'type_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'options' => $this->productType->getOptionArray(),
            ]
        );
        $this->addColumn(
            'preview_status',
            [
                'header' => __('Status'),
                'type' => 'options',
                'index' => 'status',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'options' => $this->productStatus->getOptionArray(),
            ]
        );
        $this->addColumn(
            'preview_price',
            [
                'header' => __('Price'),
                'type' => 'text',
                'index' => 'price',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'frame_callback' => [$this, 'getFormatedPrice'],
            ]
        );
        $this->addColumn(
            'attribute_set_id',
            [
                'header' => __('Attribute Set'),
                'type' => 'options',
                'index' => 'attribute_set_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'options' => $this->attributeSet->getOptionArray(),
            ]
        );
        $this->addColumn(
            'preview_visibility',
            [
                'header' => __('Visibility'),
                'type' => 'options',
                'index' => 'visibility',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
                'options' => $this->productVisibility->getOptionArray(),
            ]
        );
        return $this;
    }

    /**
     * Return grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/preview/conditionproducts', ['_current'=>true]);
    }

    /**
     * Return formatted price of product
     *
     * @param $value
     * @param $row
     *
     * @return string
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Zend_Currency_Exception
     */
    public function getFormatedPrice($value, $row)
    {
        $store = $this->storeManager->getStore();
        $currency = $this->localeCurrency->getCurrency($store->getBaseCurrencyCode());
        return $currency->toCurrency(sprintf("%f", $value));
    }
}
