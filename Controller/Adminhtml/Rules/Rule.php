<?php
/**
 * Abstract Class Rule
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Controller\Adminhtml\Rules;

abstract class Rule extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $dateFilter;

    /**
     * @var \Risecommerce\AutoRelatedProducts\Model\AutoRelatedRuleFactory
     */
    protected $ruleFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Rule constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Risecommerce\AutoRelatedProducts\Model\AutoRelatedRuleFactory $ruleFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Risecommerce\AutoRelatedProducts\Model\AutoRelatedRuleFactory $ruleFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->dateFilter = $dateFilter;
        $this->ruleFactory = $ruleFactory;
        $this->logger = $logger;
    }

    /**
     * Initiate action
     *
     * @return $this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Risecommerce_AutoRelatedProducts::auto_related_products')
            ->_addBreadcrumb(__('Auto Related Products'), __('Auto Related Products'));
        return $this;
    }

    /**
     * Returns result of current user permission check on resource and privilege
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Risecommerce_AutoRelatedProducts::auto_related_products');
    }
}
