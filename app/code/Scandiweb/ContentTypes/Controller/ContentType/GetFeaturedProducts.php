<?php

/**
 * @category  Scandiweb
 * @package   Scandiweb_ContentTypes
 * @author    Aleksandrs Kondratjevs <info@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\ContentTypes\Controller\ContentType;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Rule\Model\Condition\Combine;
use Magento\Rule\Model\Condition\Sql\Builder as SqlBuilder;
use Magento\CatalogWidget\Model\Rule;
use Magento\Widget\Helper\Conditions;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\PageBuilder\Model\Catalog\Sorting;

class GetFeaturedProducts extends Action
{
    public const DEFAULT_PRODUCTS_LIMIT = '5';

    protected PageFactory $_pageFactory;

    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var Visibility
     */
    protected $catalogProductVisibility;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var SqlBuilder
     */
    protected $sqlBuilder;

    /**
     * @var Rule
     */
    protected $rule;

    /**
     * @var Conditions
     */
    protected $conditionsHelper;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var Sorting
     */
    protected $sorting;

    /**
     * @var array
     */
    private $sortOptions;

    /**
     * @param Context $context
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        PageFactory $pageFactory,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        StoreManagerInterface $storeManager,
        SqlBuilder $sqlBuilder,
        Rule $rule,
        Conditions $conditionsHelper,
        Sorting $sorting,
        CategoryRepositoryInterface $categoryRepository = null,
        array $sortOptions = []
    ) {
        $this->_pageFactory = $pageFactory;
        $this->resultFactory = $resultFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->storeManager = $storeManager;
        $this->sqlBuilder = $sqlBuilder;
        $this->rule = $rule;
        $this->conditionsHelper = $conditionsHelper;
        $this->sorting = $sorting;
        $this->categoryRepository = $categoryRepository ?? ObjectManager::getInstance()
            ->get(CategoryRepositoryInterface::class);
        $this->sortOptions = $sortOptions;
        parent::__construct($context);
    }

    /**
     * Prepare and return product collection
     *
     * @return Collection
     * @SuppressWarnings(PHPMD.RequestAwareBlockMethod)
     * @throws LocalizedException
     */
    public function createCollection($sortOrder, $productsLimit, $conditions)
    {
        $collection = $this->getBaseCollection($sortOrder, $productsLimit, $conditions);

        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        return $collection;
    }

    /**
     * Prepare and return product collection without visibility filter
     *
     * @return Collection
     * @throws LocalizedException
     */
    public function getBaseCollection($sortOrder, $productsLimit, $conditions)
    {

        $collection = $this->productCollectionFactory->create();
        $collection->setStoreId($this->storeManager->getStore()->getId());

        /**
         * Change sorting attribute to entity_id because created_at can be the same for products fastly created
         * one by one and sorting by created_at is indeterministic in this case.
         */
        $collection
            ->addStoreFilter()
            ->addAttributeToSort('entity_id', 'desc')
            ->setPageSize($productsLimit);
        $this->sorting->applySorting($sortOrder, $collection);

        $conditions = $this->getConditions($conditions);
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);

        /**
         * Prevent retrieval of duplicate records. This may occur when multiselect product attribute matches
         * several allowed values from condition simultaneously
         */
        $collection->distinct(true);

        return $collection;
    }

    /**
     * Get conditions
     *
     * @return Combine
     */
    protected function getConditions($conditions)
    {
        if (is_string($conditions)) {
            $conditions = $this->decodeConditions($conditions);
        }

        foreach ($conditions as $key => $condition) {
            if (!empty($condition['attribute'])) {
                if (in_array($condition['attribute'], ['special_from_date', 'special_to_date'])) {
                    $conditions[$key]['value'] = date('Y-m-d H:i:s', strtotime($condition['value']));
                }

                if ($condition['attribute'] == 'category_ids') {
                    $conditions[$key] = $this->updateAnchorCategoryConditions($condition);
                }
            }
        }

        $this->rule->loadPost(['conditions' => $conditions]);
        return $this->rule->getConditions();
    }

    /**
     * Decode encoded special characters and unserialize conditions into array
     *
     * @param string $encodedConditions
     * @return array
     * @see \Magento\Widget\Model\Widget::getDirectiveParam
     */
    private function decodeConditions(string $encodedConditions): array
    {
        return $this->conditionsHelper->decode(htmlspecialchars_decode($encodedConditions));
    }

    /**
     * Update conditions if the category is an anchor category
     *
     * @param array $condition
     * @return array
     */
    private function updateAnchorCategoryConditions(array $condition): array
    {
        if (array_key_exists('value', $condition)) {
            $categoryId = $condition['value'];

            try {
                $category = $this->categoryRepository->get($categoryId, $this->storeManager->getStore()->getId());
            } catch (NoSuchEntityException $e) {
                return $condition;
            }

            $children = $category->getIsAnchor() ? $category->getChildren(true) : [];
            if ($children) {
                $children = explode(',', $children);
                $condition['operator'] = "()";
                $condition['value'] = array_merge([$categoryId], $children);
            }
        }

        return $condition;
    }

    /**
     * @return void
     */
    public function execute()
    {
        // Slider configurations
        $isSliderInfinitive = $this->getRequest()->getParam('is_slider_infinitive');
        $isSliderShowPagination = $this->getRequest()->getParam('is_slider_show_pagination');
        $isSliderPaginationProgressbar = $this->getRequest()->getParam('is_slider_pagination_progressbar');
        $isSliderShowArrows = $this->getRequest()->getParam('is_slider_show_arrows');

        // Products configurations
        $productsLimit = $this->getRequest()->getParam('products_limit') ?? self::DEFAULT_PRODUCTS_LIMIT;
        $conditionType = $this->getRequest()->getParam('condition_type');
        $categoryId = $this->getRequest()->getParam('category_id');
        $conditions = $this->getRequest()->getParam('conditions');
        $sortOrder = $this->getRequest()->getParam('sort_order');
        $skus = $this->getRequest()->getParam('sku');
        $productIds = [];

        $page = $this->_pageFactory->create();

        $slider = $page->getLayout()->createBlock(Template::class)
            ->setTemplate('Magento_Catalog::product/slider/product-slider.phtml')
            ->setData('is_slider_infinitive', $isSliderInfinitive)
            ->setData('is_slider_show_pagination', $isSliderShowPagination)
            ->setData('is_slider_pagination_progressbar', $isSliderPaginationProgressbar)
            ->setData('is_slider_show_arrows', $isSliderShowArrows)
            ->setData('sort_order', $sortOrder)
            ->setData('page_size', $productsLimit);

        switch ($conditionType) {
            case 'condition':
                $productCollection = $this->createCollection($sortOrder, $productsLimit, $conditions);
                $skus = implode(',', $productCollection->getColumnValues('sku'));
                $productIds = $productCollection->getColumnValues('entity_id');
                $slider->setData('product_skus', $skus);
                break;
            case 'sku':
                $slider->setData('product_skus', $skus);
                break;
            case 'category_ids':
                $slider->setData('category_ids', $categoryId);
                break;
        }

        if (isset($this->sortOptions[$sortOrder])) {
            $sortOrderFormatted =  $this->sortOptions[$sortOrder];

            foreach ($sortOrderFormatted as $key => $value) {
                $slider->setData('sort_attribute', $key);
                $slider->setData('sort_direction', $value);
            }
        }

        $sliderHtml = $slider->toHtml();

        if ($sliderHtml) {
            $priceBoxHtml = $page->getLayout()->createBlock(Template::class)
                ->setTemplate('Magento_Catalog::product/list/js/price-box.phtml')
                ->toHtml();

            $compareHtml = $page->getLayout()->createBlock(Template::class)
                ->setTemplate('Magento_Catalog::product/list/js/compare.phtml')
                ->toHtml();

            $wishlistHtml = $page->getLayout()->createBlock(Template::class)
                ->setTemplate('Magento_Catalog::product/list/js/wishlist.phtml')
                ->toHtml();


            $response = $wishlistHtml . $priceBoxHtml . $compareHtml . $sliderHtml;

            $this->getResponse()->setBody($response);
        } else {
            $this->getResponse()->setBody('');
        }

        $cacheTags = $this->getRequest()->getParam('cache_tags')  ?? [];

        if ($categoryId) {
            $cacheTags[] = Category::CACHE_TAG . '_' . $categoryId;
        }

        if (count($productIds)) {
            foreach ($productIds as $id) {
                $cacheTags[] = Product::CACHE_TAG . '_' . $id;
            }
        }

        $cacheTags  = implode(', ', $cacheTags);

        $this->getResponse()->setHeader('X-Magento-Tags', $cacheTags, true);
    }
}
