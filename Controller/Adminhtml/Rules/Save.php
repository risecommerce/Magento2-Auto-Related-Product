<?php
/**
 * Class Save
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

class Save extends \Risecommerce\AutoRelatedProducts\Controller\Adminhtml\Rules\Rule
{
    /**
     * Rule save action
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        if (!$this->getRequest()->getPostValue()) {
            $this->_redirect('risecommerce_auto_related_products/rules/*/');
        }

        try {
            $model = $this->ruleFactory->create();
            $data = $this->getRequest()->getPostValue();

            $filterValues = ['start_date' => $this->dateFilter];
            if ($this->getRequest()->getParam('end_date')) {
                $filterValues['end_date'] = $this->dateFilter;
            }
            $inputFilter = new \Zend_Filter_Input(
                $filterValues,
                [],
                $data
            );
            $data = $inputFilter->getUnescaped();
            if (!$data['end_date']) {
                $data['end_date'] = null;
            }
            if (!isset($data['display_mode'])) {
                if (!empty($data['not_display_products']) && (in_array(1, $data['not_display_products']) || in_array(2, $data['not_display_products']))) {
                    $data['display_mode'] = 1;
                } else {
                    $data['display_mode'] = 0;
                }
            }
            if (!isset($data['no_of_products'])) {
                $data['no_of_products'] = 1;
            }

            if (!isset($data['not_display_products'])) {
                $data['not_display_products'] = [];
            }

            if (!isset($data['add_products'])) {
                $data['add_products'] = [];
            }

            if (!isset($data['additional_info'])) {
                $data['additional_info'] = [];
            }

            $id = $this->getRequest()->getParam('rule_id');
            if ($id) {
                $model->load($id);
            }

            $validateResult = $model->validateData(new \Magento\Framework\DataObject($data));
            if ($validateResult !== true) {
                foreach ($validateResult as $errorMessage) {
                    $this->messageManager->addErrorMessage($errorMessage);
                }
                $this->_session->setPageData($data);
                $this->_redirect('risecommerce_auto_related_products/rules/edit', ['id' => $model->getId()]);
                return;
            }

            $data = $this->prepareData($data);
            unset($data['conditions_serialized']);
            unset($data['actions_serialized']);
            $model->loadPost($data);

            $this->_session->setPageData($model->getData());

            $model->save();
            $this->messageManager->addSuccessMessage(__('You saved the rule.'));
            $this->_session->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('risecommerce_auto_related_products/rules/edit', ['id' => $model->getId(), 'type' => $model->getRuleType()]);
                return;
            }
            $this->_redirect('risecommerce_auto_related_products/rules/');
            return;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving the rule data. Please review the error log.')
            );
            $this->logger->critical($e);
            $data = !empty($data) ? $data : [];
            $this->_session->setPageData($data);
            $this->_redirect(
                'risecommerce_auto_related_products/rules/edit',
                [
                    'id' => $this->getRequest()->getParam('rule_id'),
                    'type' => $data['rule_type']
                ]
            );
            return;
        }
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

        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }

        if (isset($data['rule']['actions'])) {
            $data['actions'] = $data['rule']['actions'];
        }

        if (isset($data['store_ids'])) {
            $data['store_ids'] = implode(',', $data['store_ids']);
        }

        if (isset($data['customer_groups'])) {
            $data['customer_groups'] = implode(',', $data['customer_groups']);
        }

        if (isset($data['additional_info'])) {
            $data['additional_info'] = implode(',', $data['additional_info']);
        }

        if (isset($data['add_products'])) {
            $data['add_products'] = implode(',', $data['add_products']);
        }

        if (isset($data['not_display_products'])) {
            $data['not_display_products'] = implode(',', $data['not_display_products']);
        }

        unset($data['rule']);

        return $data;
    }
}
