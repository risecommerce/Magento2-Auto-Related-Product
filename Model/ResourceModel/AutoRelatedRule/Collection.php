<?php
/**
 * Class Collection
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\Store;

class Collection extends \Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection
{
    /**
     * Primary key
     *
     * @var string
     */
    protected $_idFieldName = 'rule_id';

    /**
     * Collection constructor.
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null $resource
     * @param Json|null $serializer
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null,
        Json $serializer = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->_associatedEntitiesMap = $this->getAssociatedEntitiesMap();
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Risecommerce\AutoRelatedProducts\Model\AutoRelatedRule::class,
            \Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule::class
        );
    }

    /**
     * Map associated entities
     *
     * @param $entityType
     * @param $objectField
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function mapAssociatedEntities($entityType, $objectField)
    {
        if (!$this->_items) {
            return;
        }

        $entityInfo = $this->_getAssociatedEntityInfo($entityType);
        $ruleIdField = $entityInfo['rule_id_field'];
        $entityIds = $this->getColumnValues($ruleIdField);

        $select = $this->getConnection()->select()->from(
            $this->getTable($entityInfo['associations_table'])
        )->where(
            $ruleIdField . ' IN (?)',
            $entityIds
        );

        $associatedEntities = $this->getConnection()->fetchAll($select);

        array_map(function ($associatedEntity) use ($entityInfo, $ruleIdField, $objectField) {
            $item = $this->getItemByColumnValue($ruleIdField, $associatedEntity[$ruleIdField]);
            $itemAssociatedValue = $item->getData($objectField) === null ? [] : $item->getData($objectField);
            $itemAssociatedValue[] = $associatedEntity[$entityInfo['entity_id_field']];
            $item->setData($objectField, $itemAssociatedValue);
        }, $associatedEntities);
    }

    /**
     * After load add mapped entities
     *
     * @return \Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _afterLoad()
    {
        $this->mapAssociatedEntities('store', 'store_ids');
        $this->mapAssociatedEntities('customer_group', 'customer_group_ids');

        $this->setFlag('add_websites_to_result', false);

        return parent::_afterLoad();
    }

    /**
     * Return mapped entities
     *
     * @return mixed
     */
    private function getAssociatedEntitiesMap()
    {
        if (!$this->_associatedEntitiesMap) {
            $this->_associatedEntitiesMap = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Risecommerce\AutoRelatedProducts\Model\ResourceModel\Rule\AssociatedEntityMap::class)
                ->getData();
        }
        return $this->_associatedEntitiesMap;
    }

    /**
     * Filter by customer groups
     *
     * @param $customerGroupId
     *
     * @return $this
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addCustomerGroupFilter($customerGroupId)
    {
        $entityInfo = $this->_getAssociatedEntityInfo('customer_group');
        if (!$this->getFlag('is_customer_group_joined')) {
            $this->setFlag('is_customer_group_joined', true);
            $this->getSelect()->join(
                ['customer_group' => $this->getTable($entityInfo['associations_table'])],
                $this->getConnection()
                    ->quoteInto('customer_group.' . $entityInfo['entity_id_field'] . ' = ?', $customerGroupId)
                . ' AND main_table.' . $entityInfo['rule_id_field'] . ' = customer_group.'
                . $entityInfo['rule_id_field'],
                []
            );
        }
        return $this;
    }

    /**
     * Filter by store ids
     *
     * @param $storeId
     *
     * @return $this
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addStoreFilter($storeId)
    {
        $storeIds = [
            Store::DEFAULT_STORE_ID,
            $storeId
        ];
        $entityInfo = $this->_getAssociatedEntityInfo('store');
        if (!$this->getFlag('is_store_joined')) {
            $this->setFlag('is_store_joined', true);
            $this->getSelect()->join(
                ['store' => $this->getTable($entityInfo['associations_table'])],
                $this->getConnection()
                    ->quoteInto('store.' . $entityInfo['entity_id_field'] . ' in (?)', $storeIds)
                . ' AND main_table.' . $entityInfo['rule_id_field'] . ' = store.'
                . $entityInfo['rule_id_field'],
                []
            );
        }
        return $this;
    }

    /**
     * Filter by rule id
     *
     * @param $ruleId
     *
     * @return $this
     */
    public function addRuleIdFilter($ruleId)
    {
        $this->getSelect()->where('main_table.rule_id = ?', $ruleId);
        return $this;
    }
}
