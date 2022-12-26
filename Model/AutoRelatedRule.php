<?php
/**
 * Class AutoRelatedRule
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Model;

use Magento\Rule\Model\AbstractModel;


class AutoRelatedRule extends AbstractModel implements \Risecommerce\AutoRelatedProducts\Api\Data\AutoRelatedRuleInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'risecommerce_auto_related_products';

    /**
     * @var string
     */
    protected $_eventObject = 'rule';

    /**
     * @var \Magento\CatalogRule\Model\Rule\Condition\CombineFactory
     */
    protected $catalogCondCombineFactory;

    /**
     * @var \Magento\SalesRule\Model\Rule\Condition\CombineFactory
     */
    protected $salesCondCombineFactory;

    /**
     * @var \Magento\Rule\Model\Condition\CombineFactory
     */
    protected $ruleCombineFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var ResourceModel\AutoRelatedRule
     */
    protected $ruleResourceModel;

    /**
     * AutoRelatedRule constructor.
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $catalogCondCombineFactory
     * @param \Magento\SalesRule\Model\Rule\Condition\CombineFactory $salesCondCombineFactory
     * @param \Magento\Rule\Model\Condition\CombineFactory $ruleCombineFactory
     * @param \Magento\Framework\App\RequestInterface $request
     * @param ResourceModel\AutoRelatedRule $ruleResourceModel
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $catalogCondCombineFactory,
        \Magento\SalesRule\Model\Rule\Condition\CombineFactory $salesCondCombineFactory,
        \Magento\Rule\Model\Condition\CombineFactory $ruleCombineFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule $ruleResourceModel,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->catalogCondCombineFactory = $catalogCondCombineFactory;
        $this->salesCondCombineFactory = $salesCondCombineFactory;
        $this->ruleCombineFactory = $ruleCombineFactory;
        $this->request = $request;
        $this->ruleResourceModel = $ruleResourceModel;
        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }

    /**
     * Set resource model and Id field name
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(\Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule::class);
        $this->setIdFieldName('rule_id');
    }

    /**
     * Get rule condition combine model instance
     *
     * @return \Magento\CatalogRule\Model\Rule\Condition\Combine|\Magento\Rule\Model\Condition\Combine|\Magento\SalesRule\Model\Rule\Condition\Combine
     */
    public function getConditionsInstance()
    {
        if ($this->request->getParam('type') == 'product' || $this->getData('rule_type') == 'product') {
            return $this->catalogCondCombineFactory->create();
        } elseif ($this->request->getParam('type') == 'cart' ||
            $this->request->getParam('type') == 'checkout' ||
            $this->getData('rule_type') == 'cart' ||
            $this->getData('rule_type') == 'checkout'
        ) {
            return $this->salesCondCombineFactory->create();
        } else {
            return $this->ruleCombineFactory->create();
        }
    }

    /**
     * Get rule action product combine model instance
     *
     * @return \Magento\CatalogRule\Model\Rule\Condition\Combine|\Magento\Rule\Model\Action\Collection
     */
    public function getActionsInstance()
    {
        return $this->catalogCondCombineFactory->create();
    }

    /**
     * Return Customer group ids of rule
     *
     * @return mixed
     */
    public function getCustomerGroupIds()
    {
        if (!$this->hasCustomerGroupIds()) {
            $customerGroupIds = $this->ruleResourceModel->getCustomerGroupIds($this->getId());
            $this->setData('customer_group_ids', (array)$customerGroupIds);
        }
        return $this->_getData('customer_group_ids');
    }
}
