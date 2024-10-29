<?php
/**
 * Class Options
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Model\Product\AttributeSet;

class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    protected $collectionFactory;
    protected $product;

    /**
     * Options constructor.
     *
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $collectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product $product
     */
    public function __construct(
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product $product
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->product = $product;
    }

    /**
     * @inheritDoc
     * Get array product attribute set options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $this->options = $this->collectionFactory->create()
            ->setEntityTypeFilter($this->product->getTypeId())
            ->toOptionArray();

        return $this->options;
    }

    /**
     * return product attribute set options
     *
     * @return array
     */
    public function getOptionArray()
    {
        $options = [];
        foreach ($this->toOptionArray() as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }
}
