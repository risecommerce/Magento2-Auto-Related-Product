<?php
/**
 * Class Ajax
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\DisplayModes;
use Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule\CollectionFactory;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\RuleStatus;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\Position;

class Ajax extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule\CollectionFactory
     */
    protected $autorelatedCollectionFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $customerSession;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Ajax constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CollectionFactory $autorelatedCollectionFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CollectionFactory $autorelatedCollectionFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\SessionFactory $customerSession,
        JsonFactory $resultJsonFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->autorelatedCollectionFactory = $autorelatedCollectionFactory;
        $this->timezone = $timezone;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);
    }

    /**
     * Rules ajax action
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $resultPage = $this->resultPageFactory->create(ResultFactory::TYPE_PAGE);
            $resultData = [];
            $result = $this->resultJsonFactory->create();
            try {
                $ruleCollection = $this->getRulesCollection();

                if (count($ruleCollection)) {
                    foreach ($ruleCollection as $rule) {
                        $resultPage->addHandle('empty');
                        $output = $resultPage->getLayout()
                            ->createBlock(\Risecommerce\AutoRelatedProducts\Block\ProductList\AutoRelated::class)
                            ->setData('rule', $rule)
                            ->setData('rule_id', $this->getRequest()->getParam('rule_id'))
                            ->setData('mode', DisplayModes::AJAX)
                            ->setTemplate('Risecommerce_AutoRelatedProducts::auto_related_products_rules.phtml')
                            ->toHtml();
                        $resultData['rules'][$rule->getRuleId()] = [
                            'position' => $rule->getPosition(),
                            'html' => $output
                        ];
                    }
                    $resultData['hasRules'] = true;
                } else {
                    $resultData = [
                        'hasRules' => false
                    ];
                }
                $resultData['success'] = true;
            } catch (Exception $e) {
                $resultData = [
                    'success' => false
                ];
            }

            $result->setData(['data' => $resultData]);
            return $result;
        } else {
            $this->_redirect('home');
        }
    }

    /**
     * Return collection of rules on ajax call
     *
     * @return \Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule\Collection
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRulesCollection()
    {
        $ruleCollection = $this->autorelatedCollectionFactory->create()
            ->addFieldToFilter('status', RuleStatus::ENABLED);

        $currentDate = $this->timezone->date()->format('Y-m-d');

        if ($this->getRequest()->getParam('rule_id')) {
            $ruleCollection->addRuleIdFilter($this->getRequest()->getParam('rule_id'));
            $ruleCollection->addFieldToFilter('position', Position::CUSTOM);
        } else {
            $ruleCollection->addFieldToFilter('rule_type', $this->getRequest()->getParam('type'));
            $ruleCollection->addFieldToFilter('display_mode', DisplayModes::AJAX);
        }

        $ruleCollection->addStoreFilter($this->storeManager->getStore()->getId());
        $ruleCollection->addCustomerGroupFilter($this->getCustomerGroupId());
        $ruleCollection->addFieldToFilter('start_date', [['lteq' => $currentDate], ['null' => true]]);
        $ruleCollection->addFieldToFilter('end_date', [['gteq' => $currentDate], ['null' => true]]);
        $ruleCollection->setOrder('priority', 'asc');
        $ruleCollection->getSelect()->group('main_table.rule_id');
        return $ruleCollection;
    }

    /**
     * Get customer group id
     *
     * @return int
     */
    public function getCustomerGroupId()
    {
        if ($this->customerSession->create()->isLoggedIn()) {
            return $this->customerSession->create()->getCustomer()->getGroupId();
        }
        return 0;
    }
}
