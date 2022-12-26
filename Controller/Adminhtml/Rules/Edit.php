<?php
/**
 * Class Edit
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

class Edit extends \Risecommerce\AutoRelatedProducts\Controller\Adminhtml\Rules\Rule
{
    /**
     * Edit action
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->ruleFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getRuleId()) {
                $this->messageManager->addErrorMessage(__('The rule no longer exists.'));
                $this->_redirect('risecommerce_auto_related_products/rules/*');
                return;
            }
        }

        // set entered data if was error when we do save
        $data = $this->_session->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $model->getConditions()->setFormName('risecommerce_auto_related_products_rule_form');
        $model->getConditions()->setJsFormObject($model->getConditionsFieldSetId($model->getConditions()->getFormName()));
        $model->getActions()->setFormName('risecommerce_auto_related_products_rule_form');
        $model->getActions()->setJsFormObject($model->getActionsFieldSetId($model->getActions()->getFormName()));
        $this->coreRegistry->register('Risecommerce_AutoRelatedProducts', $model);

        $this->_initAction();
        $this->_view->getLayout()
            ->getBlock('risecommerce_auto_related_products_rule_edit')
            ->setData('action', $this->getUrl('risecommerce_auto_related_products/rules/save'));

        $this->_addBreadcrumb($id ? __('Edit Rule') : __('New Rule'), $id ? __('Edit Rule') : __('New Rule'));

        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getRuleId() ? $model->getRuleName() : __('New Rule')
        );
        $this->_view->renderLayout();
    }
}
