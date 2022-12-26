<?php
/**
 * Class Chooser
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Block\Adminhtml;

use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\Multiselect;
use Magento\Framework\Escaper;

class Chooser extends Multiselect
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    public $collectionFactory;

    /**
     * Chooser constructor.
     *
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
    }

    /**
     * Return element html
     *
     * @return string
     */
    public function getElementHtml()
    {
        $html = '<div class="admin__field-control admin__control-grouped">';
        $html .= '<div id="risecommerce-auto-related-products-category-select" 
            class="admin__field" 
            data-bind="scope:\'risecommerceAutoRelatedProductsCategory\'" 
            data-index="index">';
        $html .= '<!-- ko foreach: elems() -->';
        $html .= '<input name="category_ids" data-bind="value: value" style="display: none"/>';
        $html .= '<!-- ko template: elementTmpl --><!-- /ko -->';
        $html .= '<!-- /ko -->';
        $html .= '</div></div>';

        $html .= $this->getAfterElementHtml();

        return $html;
    }

    /**
     * Return categories tree
     *
     * @return mixed
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategoriesTree()
    {
        /* @var $collection \Magento\Catalog\Model\ResourceModel\Category\Collection */
        $collection = $this->collectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToSort('position', 'asc');

        $categoryById = [
            CategoryModel::TREE_ROOT_ID => [
                'value'    => CategoryModel::TREE_ROOT_ID,
                'optgroup' => null,
            ],
        ];

        foreach ($collection as $category) {
            foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                if (!isset($categoryById[$categoryId])) {
                    $categoryById[$categoryId] = ['value' => $categoryId];
                }
            }

            $categoryById[$category->getId()]['is_active'] = 1;
            $categoryById[$category->getId()]['label'] = $category->getName();
            $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
        }

        return $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
    }

    /**
     * Get values for category
     *
     * @return array
     */
    public function getValues()
    {
        $values = $this->getValue();
        if (!is_array($values)) {
            $values = explode(',', $values);
        }

        if (count($values) <= 0) {
            return [];
        }

        $collection = $this->collectionFactory->create()
            ->addIdFilter($values);

        $options = [];
        foreach ($collection as $category) {
            $options[] = $category->getId();
        }

        return $options;
    }

    /**
     * Get after element html
     *
     * @return mixed|string
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAfterElementHtml()
    {
        $html = '<script type="text/x-magento-init">
            {
                "*": {
                    "Magento_Ui/js/core/app": {
                        "components": {
                            "risecommerceAutoRelatedProductsCategory": {
                                "component": "uiComponent",
                                "children": {
                                    "select_category": {
                                        "component": "Risecommerce_AutoRelatedProducts/js/components/new-category",
                                        "config": {
                                            "filterOptions": true,
                                            "disableLabel": true,
                                            "chipsEnabled": true,
                                            "levelsVisibility": "1",
                                            "elementTmpl": "ui/grid/filters/elements/ui-select",
                                            "options": ' . json_encode($this->getCategoriesTree()) . ',
                                            "value": ' . json_encode($this->getValues()) . ',
                                            "listens": {
                                                "index=create_category:responseData": "setParsed",
                                                "newOption": "toggleOptionSelected"
                                            },
                                            "config": {
                                                "dataScope": "select_category",
                                                "sortOrder": 10
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        </script>';

        return $html;
    }
}
