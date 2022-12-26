<?php
/**
 * Class AutoRelated
 *
 * PHP version 7
 *
 * @category Risecommerce
 * @package  Risecommerce_AutoRelatedProducts
 * @author   Risecommerce <magento@risecommerce.com>
 * @license  https://www.risecommerce.com  Open Software License (OSL 3.0)
 * @link     https://www.risecommerce.com
 */
namespace Risecommerce\AutoRelatedProducts\Block\ProductList;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\RuleStatus;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\AdditionalInfo;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\ProductOrder;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\AddProducts;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\NotDisplayProducts;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\DisplayLayouts;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\DisplayModes;
use Risecommerce\AutoRelatedProducts\Ui\Component\Form\Position;

class AutoRelated extends \Magento\Framework\View\Element\Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'Risecommerce_AutoRelatedProducts::auto_related_products.phtml';

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $postHelper;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;

    /**
     * @var \Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule\CollectionFactory
     */
    protected $autorelatedCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    protected $abstractProduct;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Wishlist\Helper\Data
     */
    protected $wishlistHelper;

    /**
     * @var \Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory
     */
    protected $wishlistCollectionFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    /**
     * @var \Magento\Catalog\Helper\Product\Compare
     */
    protected $compareProduct;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $customerSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $productStatus;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $productVisibility;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * FormKey
     *
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    /**
     * AutoRelated constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     * @param \Magento\Framework\Data\Helper\PostHelper $postHelper
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule\CollectionFactory $autorelatedCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Block\Product\ListProduct $abstractProduct
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Wishlist\Helper\Data $wishlistHelper
     * @param \Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory $wishlistCollectionFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Magento\Catalog\Helper\Product\Compare $compareProduct
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\Data\Helper\PostHelper $postHelper,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Risecommerce\AutoRelatedProducts\Model\ResourceModel\AutoRelatedRule\CollectionFactory $autorelatedCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Block\Product\ListProduct $abstractProduct,
        ProductRepositoryInterface $productRepository,
        \Magento\Wishlist\Helper\Data $wishlistHelper,
        \Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory $wishlistCollectionFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Magento\Catalog\Helper\Product\Compare $compareProduct,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    ) {
        $this->redirect = $redirect;
        $this->postHelper = $postHelper;
        $this->urlHelper = $urlHelper;
        $this->autorelatedCollectionFactory = $autorelatedCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->abstractProduct = $abstractProduct;
        $this->productRepository = $productRepository;
        $this->wishlistHelper = $wishlistHelper;
        $this->wishlistCollectionFactory = $wishlistCollectionFactory;
        $this->checkoutSession = $checkoutSession;
        $this->jsonSerializer = $jsonSerializer;
        $this->compareProduct = $compareProduct;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->timezone = $timezone;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->cart = $cart;
        $this->formKey = $formKey;
        parent::__construct($context, $data);
    }

    /**
     * Return matched product ids to display
     *
     * @param $rule
     *
     * @return mixed
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductIds($rule)
    {
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect('*');
        $actionProducts = [];
        foreach ($productCollection as $product) {
            if ($rule->getActions()->validate($product)) {
                $actionProducts[] = $product->getId();
            }
        }

        if ($rule->getRuleType() == 'product') {
            $productCollection->addFieldToFilter(
                'entity_id',
                ['neq' => $this->getRequest()->getParam('id')]
            );
            $addProducts = explode(',', $rule->getAddProducts());

            if (in_array(AddProducts::RELATED, $addProducts)) {
                if($this->getProduct() != NULL){
                    $relatedProductIds = $this->getProduct()->getRelatedProductIds();
                    $actionProducts = array_merge($actionProducts, $relatedProductIds);
                }
            }
            if (in_array(AddProducts::UPSELL, $addProducts)) {
                if($this->getProduct() != NULL){
                    $upsellProductIds = $this->getProduct()->getUpSellProductIds();
                    $actionProducts = array_merge($actionProducts, $upsellProductIds);
                }
            }
            if (in_array(AddProducts::CROSSSELL, $addProducts)) {
                if($this->getProduct() != NULL){
                    $crossSellProductIds = $this->getProduct()->getCrossSellProductIds();
                    $actionProducts = array_merge($actionProducts, $crossSellProductIds);
                }
            }
        }

        $notDisplayProductsIn = explode(',', $rule->getNotDisplayProducts());

        $finalActionProducts = [];
        foreach ($actionProducts as $actionProduct) {
            if (in_array(NotDisplayProducts::WISHLIST, $notDisplayProductsIn) &&
                $this->isProductInWishlist($actionProduct)
            ) {
                continue;
            } elseif (in_array(NotDisplayProducts::CART, $notDisplayProductsIn) &&
                $this->isProductInCart($actionProduct)
            ) {
                continue;
            } else {
                $finalActionProducts[] = $actionProduct;
            }
        }

        $productCollection->addAttributeToSelect('*');
        $productCollection->addAttributeToFilter('entity_id', ['in' => $finalActionProducts]);
        $productCollection->joinField(
            'is_in_stock',
            'cataloginventory_stock_item',
            'is_in_stock',
            'product_id=entity_id',
            '{{table}}.is_in_stock=1',
            'left'
        );
        if (!$rule->getDisplayOutOfStock()) {
            $productCollection->addFieldToFilter('is_in_stock', 1);
        }
        $productCollection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
        $productCollection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $actionProductCollection = $this->getSortedCollection($productCollection, $rule->getProductOrder());
        if ($this->isPositionPopup($rule)) {
            $actionProductCollection->setPageSize(1);
        } else {
            $actionProductCollection->setPageSize($rule->getNoOfProducts());
        }
        $actionProductCollection->clear();
        return $actionProductCollection;
    }

    /**
     * Check if product is added to wishlist
     *
     * @param $productId
     *
     * @return bool
     */
    public function isProductInWishlist($productId)
    {
        $wishlistItems = $this->wishlistCollectionFactory->create()
            ->addCustomerIdFilter(
                $this->customerSession->create()->getCustomer()->getId()
            )
            ->addFieldToFilter('product_id', $productId);
        if (count($wishlistItems) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Check if product is added to cart ot not
     *
     * @param $productId
     *
     * @return bool
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isProductInCart($productId)
    {
        return $this->checkoutSession->getQuote()->hasProductId($productId);
    }

    /**
     * Sort product collection according to rule product sort order
     *
     * @param $collection
     * @param $order
     *
     * @return mixed
     */
    public function getSortedCollection($collection, $order)
    {
        switch ($order) {
            case ProductOrder::BESTSELLER:
                $collection->getSelect()->joinLeft(
                    $collection->getTable('sales_order_item'),
                    'e.entity_id = '.$collection->getTable('sales_order_item').'.product_id',
                    ['qty_ordered'=>'SUM('.$collection->getTable('sales_order_item').'.qty_ordered)']
                )
                    ->group('e.entity_id')
                    ->order('qty_ordered desc');
                break;

            case ProductOrder::LOW_TO_HIGH_PRICE:
                $collection->setOrder('price', 'asc');
                break;

            case ProductOrder::HIGH_TO_LOW_PRICE:
                $collection->setOrder('price', 'desc');
                break;

            case ProductOrder::NEW_ARRIVALS:
                $collection->setOrder('created_at', 'desc');
                break;

            default:
                $collection;
                break;
        }
        return $collection;
    }

    /**
     * Return rules collection for current page
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

        if ($this->getData('rule_id')) {
            $ruleCollection->addRuleIdFilter($this->getRuleId());
            $ruleCollection->addFieldToFilter('position', Position::CUSTOM);
        } else {
            if ($this->getRequest()->getFullActionName() == 'catalog_product_view') {
                $ruleCollection->addFieldToFilter('rule_type', 'product');
            } elseif ($this->getRequest()->getFullActionName() == 'catalog_category_view') {
                $ruleCollection->addFieldToFilter('rule_type', 'category');
            } elseif ($this->getRequest()->getFullActionName() == 'checkout_cart_index') {
                $ruleCollection->addFieldToFilter('rule_type', 'cart');
            } elseif ($this->getRequest()->getFullActionName() == 'checkout_index_index') {
                $ruleCollection->addFieldToFilter('rule_type', 'checkout');
            }
            $ruleCollection->addFieldToFilter('display_mode', $this->getData('mode'));
            if ($this->getData('mode') == DisplayModes::BLOCK) {
                $ruleCollection->addFieldToFilter('position', $this->getData('position'));
            }
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
     * Return customer group id
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

    /**
     * Check if rule can be display on current page or not
     *
     * @param $rule
     *
     * @return bool
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function canShowRelatedBlock($rule)
    {
        if ($this->getData('rule_id')) {
            if ($rule->getRuleType() == 'cart' || $rule->getRuleType() == 'checkout') {
				$totalItems = $this->cart->getQuote()->getItemsQty();
                $quote = $this->checkoutSession->getQuote();
                if ($quote->isVirtual()) {
                    $address = $quote->getBillingAddress();
                } else {
                    $address = $quote->getShippingAddress();
                }
				$address->setData('total_qty',$totalItems);
                return $rule->validate($address);
            } else {
                return true;
            }
        }
        if ($rule->getRuleType() == 'product') {
            if ($rule->getConditions()->validate($this->getProduct())) {
                return true;
            }
            return false;
        } elseif ($rule->getRuleType() == 'category') {
            if ($rule->getCategoryIds() != null) {
                $categoryIds = explode(',', $rule->getCategoryIds());
                if (in_array($this->getRequest()->getParam('id'), $categoryIds)) {
                    return true;
                }
            } else {
                return true;
            }
            return false;
        } elseif ($rule->getRuleType() == 'cart' || $rule->getRuleType() == 'checkout') {
            $quote = $this->checkoutSession->getQuote();
			$totalItems = $this->cart->getQuote()->getItemsQty();
            if ($quote->isVirtual()) {
                $address = $quote->getBillingAddress();
            } else {
                $address = $quote->getShippingAddress();
            }
			$address->setData('total_qty',$totalItems);
			
            return $rule->validate($address);
        } 
    }

    /**
     * Return product block object
     *
     * @return \Magento\Catalog\Block\Product\ListProduct
     */
    public function getProductBlock()
    {
        return $this->abstractProduct;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * Return current product
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface|\Magento\Catalog\Model\Product
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProduct()
    {
        if ($this->getData('mode') == DisplayModes::AJAX) {
            if($this->getRequest()->getParam('id')){
                return $this->productRepository->getById($this->getRequest()->getParam('id'));
            }else{
                return NULL;
            }

        }
        return $this->abstractProduct->getProduct();
    }

    /**
     * Check if price can be display for rule
     *
     * @param $rule
     *
     * @return bool
     */
    public function canShowPrice($rule)
    {
        $additionalInfo = explode(',', $rule->getAdditionalInfo());
        if (in_array(AdditionalInfo::PRICE, $additionalInfo)) {
            return true;
        }
        return false;
    }

    /**
     * Check if review can be display for rule
     *
     * @param $rule
     *
     * @return bool
     */
    public function canShowReview($rule)
    {
        $additionalInfo = explode(',', $rule->getAdditionalInfo());
        if (in_array(AdditionalInfo::REVIEW, $additionalInfo)) {
            return true;
        }
        return false;
    }

    /**
     * Check if add-to-cart can be display for rule
     *
     * @param $rule
     *
     * @return bool
     */
    public function canShowAddToCart($rule)
    {
        $additionalInfo = explode(',', $rule->getAdditionalInfo());
        if (in_array(AdditionalInfo::ADD_TO_CART, $additionalInfo)) {
            return true;
        }
        return false;
    }

    /**
     * Check if add-to-wishlist can be display for rule
     *
     * @param $rule
     *
     * @return bool
     */
    public function canShowAddToWishlist($rule)
    {
        $additionalInfo = explode(',', $rule->getAdditionalInfo());
        if (in_array(AdditionalInfo::ADD_TO_WISHLIST, $additionalInfo)) {
            return true;
        }
        return false;
    }

    /**
     * Check if add-to-compare can be display for rule
     *
     * @param $rule
     *
     * @return bool
     */
    public function canShowAddToCompare($rule)
    {
        $additionalInfo = explode(',', $rule->getAdditionalInfo());
        if (in_array(AdditionalInfo::ADD_TO_COMPARE, $additionalInfo)) {
            return true;
        }
        return false;
    }

    /**
     * Return post params for compare product
     *
     * @param $product
     * @param $rule
     *
     * @return string
     */
    public function getComparePostDataParams($product, $rule)
    {
        $params = ['product' => $product->getId()];
        $requestingPageUrl = $this->getCompareReturnUrl($rule);

        if (!empty($requestingPageUrl)) {
            $encodedUrl = $this->urlHelper->getEncodedUrl($requestingPageUrl);
            $params[\Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED] = $encodedUrl;
        }

        return $this->postHelper->getPostData($this->compareProduct->getAddUrl(), $params);
    }

    /**
     * Return return url after add-to-compare
     *
     * @param $rule
     *
     * @return string
     */
    public function getCompareReturnUrl($rule)
    {
        if ($rule->getDisplayMode() == DisplayModes::AJAX) {
            return $this->redirect->getRefererUrl();
        } else {
            return $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
        }
    }

    /**
     * Return json serialized options for product wishlist
     *
     * @param $product
     *
     * @return bool|false|string
     */
    public function getWishlistOptionsJson($product)
    {
        return $this->jsonSerializer->serialize($this->getWishlistOptions($product));
    }

    /**
     * Get product wishlist options
     *
     * @param $product
     *
     * @return array
     */
    public function getWishlistOptions($product)
    {
        return ['productType' => $this->escapeHtml($product->getTypeId())];
    }

    /**
     * Return product add-to-wishlist params
     *
     * @param $product
     *
     * @return string
     */
    public function getWishlistParams($product)
    {
        return $this->wishlistHelper->getAddParams($product);
    }

    /**
     * Check if wishlist is allowed or not
     *
     * @return bool
     */
    public function isWishListAllowed()
    {
        return $this->wishlistHelper->isAllow();
    }

    /**
     * Get current category or product id
     *
     * @return int|mixed|null
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItemId()
    {
        if ($this->getData('rule_type') == 'product') {
            return $this->getProduct()->getId();
        } elseif ($this->getData('rule_type') == 'category') {
            return $this->getRequest()->getParam('id');
        } else {
            return 0;
        }
    }

    /**
     * Check if rule position is popup
     *
     * @param $rule
     *
     * @return bool
     */
    public function isPositionPopup($rule)
    {
        return $rule->getPosition() == Position::LEFT_POPUP || $rule->getPosition() == Position::RIGHT_POPUP;
    }

    /**
     * Check if rule display mode is slider
     *
     * @param $rule
     *
     * @return bool
     */
    public function isDisplaySlider($rule)
    {
        return $rule->getDisplayLayout() == DisplayLayouts::SLIDER;
    }

    /**
     * Check if rule position is sidebar
     *
     * @param $rule
     *
     * @return bool
     */
    public function isPositionSidebar($rule)
    {
        return $rule->getPosition() == Position::BEFORE_SIDEBAR || $rule->getPosition() == Position::AFTER_SIDEBAR;
    }

    /**
     * Get Return url after add-to-cart
     *
     * @param $rule
     *
     * @return string
     */
    public function getCartReturnUrl($rule)
    {
        if ($rule->getDisplayMode() == DisplayModes::AJAX) {
            return $this->redirect->getRefererUrl();
        } else {
            return $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
        }
    }
}
