<?php
/**
 * Class ActionProducts
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Controller\Adminhtml\Preview;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;


class ActionProducts extends \Magento\Backend\App\Action
{
    /**
     * @var \Risecommerce\AutoRelatedProducts\Model\AutoRelatedRuleFactory
     */
    protected $ruleFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * ActionProducts constructor.
     *
     * @param Context $context
     * @param \Risecommerce\AutoRelatedProducts\Model\AutoRelatedRuleFactory $ruleFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public function __construct(
        Context $context,
        \Risecommerce\AutoRelatedProducts\Model\AutoRelatedRuleFactory $ruleFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

    /**
     * Action for Preview products for actions
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $postParams = $this->getRequest()->getParams();
        $data = $this->prepareData($postParams);
        $model = $this->ruleFactory->create();
        $model->loadPost($data);

        $matchingProductIds = [];
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        foreach ($productCollection as $product) {
            if ($model->getActions()->validate($product)) {
                $matchingProductIds[] = $product->getId();
            }
        }

        if(empty($matchingProductIds)){
            $matchingProductIds[] = 0;
        }

        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        $resultLayout->getLayout()
            ->getBlock('product.actions.list')
            ->setData('product_ids', $matchingProductIds);
        return $resultLayout;
    }

    /**
     * Prepares specific data
     *
     * @param $data
     *
     * @return mixed
     */
    protected function prepareData($data)
    {
        if (isset($data['rule']['actions'])) {
            $data['actions'] = $data['rule']['actions'];
        }

        unset($data['rule']);

        return $data;
    }

    /**
     * Grid action
     *
     * @return void
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
