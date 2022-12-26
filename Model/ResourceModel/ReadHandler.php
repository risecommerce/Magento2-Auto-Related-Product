<?php
/**
 * Class ReadHandler
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

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\AttributeInterface;

class ReadHandler implements AttributeInterface
{
    /**
     * @var \Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule|Rule
     */
    protected $ruleResource;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * ReadHandler constructor.
     *
     * @param \Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule $ruleResource
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        AutoRelatedRule $ruleResource,
        MetadataPool $metadataPool
    ) {
        $this->ruleResource = $ruleResource;
        $this->metadataPool = $metadataPool;
    }

    /**
     * ReadHandler execute
     *
     * @param string $entityType
     * @param array $entityData
     * @param array $arguments
     *
     * @return array
     *
     * @throws \Exception
     */
    public function execute($entityType, $entityData, $arguments = [])
    {
        $linkField = $this->metadataPool->getMetadata($entityType)->getLinkField();
        $entityId = $entityData[$linkField];

        $entityData['store_ids'] = $this->ruleResource->getStoreIds($entityId);
        $entityData['customer_group_ids'] = $this->ruleResource->getCustomerGroupIds($entityId);

        return $entityData;
    }
}
