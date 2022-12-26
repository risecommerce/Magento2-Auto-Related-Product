<?php
/**
 * Interface AutoRelatedRuleInterface
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Api\Data;


interface AutoRelatedRuleInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const RULE_ID          = 'rule_id';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id set faq id
     *
     * @return \Risecommerce\AutoRelatedProducts\Api\Data\AutoRelatedRuleInterface
     */
    public function setId($id);
}
