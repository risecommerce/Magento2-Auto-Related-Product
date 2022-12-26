<?php
/**
 * Class Index
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

class Index extends \Risecommerce\AutoRelatedProducts\Controller\Adminhtml\Rules\Rule
{
    /**
     * Index action
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Auto Related Products'), __('Auto Related Products'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Auto Related Products'));
        $this->_view->renderLayout();
    }
}
