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
namespace Risecommerce\AutoRelatedProducts\Model\ResourceModel;

class AutoRelatedRule extends \Magento\Rule\Model\ResourceModel\AbstractResource
{
    /**
     * @var \Magento\Framework\EntityManager\EntityManager
     */
    protected $entityManager;

    /**
     * AutoRelatedRule constructor.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        $this->_associatedEntitiesMap = $this->getAssociatedEntitiesMap();
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize ResourceModel
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('risecommerce_auto_related_products_rule', 'rule_id');
    }

    /**
     * Load the object
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param null $field
     *
     * @return $this|\Magento\Rule\Model\ResourceModel\AbstractResource
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        $this->getEntityManager()->load($object, $value);
        return $this;
    }

    /**
     * Save the object
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     *
     * @return $this|\Magento\Rule\Model\ResourceModel\AbstractResource
     *
     * @throws \Exception
     */
    public function save(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->getEntityManager()->save($object);
        return $this;
    }

    /**
     * Delete the object
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     *
     * @return $this|\Magento\Rule\Model\ResourceModel\AbstractResource
     *
     * @throws \Exception
     */
    public function delete(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->getEntityManager()->delete($object);
        return $this;
    }

    /**
     * Map associated entities
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
     * Return entity manager
     *
     * @return \Magento\Framework\EntityManager\EntityManager|mixed
     */
    private function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\EntityManager\EntityManager::class);
        }
        return $this->entityManager;
    }

    /**
     * Return store ids of rule
     *
     * @param $ruleId
     *
     * @return array
     */
    public function getStoreIds($ruleId)
    {
        return $this->getAssociatedEntityIds($ruleId, 'store');
    }
}
