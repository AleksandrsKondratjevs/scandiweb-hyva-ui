<?php

/**
 * @category  Scandiweb
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\MenuOrganizer\ViewModel;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Cms\Helper\Page;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\Node\Collection;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManager;
use ScandiPWA\MenuOrganizer\Block\Menu;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class MenuManager extends Menu implements ArgumentInterface
{
    /**
     * @var TreeFactory
     */
    protected TreeFactory $treeFactory;

    /**
     * @var string
     */
    protected string $identifier;

    /**
     * @var ProductCollectionFactory
     */
    protected ProductCollectionFactory $productCollectionFactory;


    /**
     * Mapping for products
     * example: item_id => product_sku
     *
     * @var array
     */
    protected $_productItemsSku = [];

    /**
     * @inheritDoc
     */
    public function __construct(
        Template\Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        Page $cmsPageHelper,
        StoreManager $storeManager,
        Registry $registry,
        CategoryCollectionFactory $categoryCollectionFactory,
        ProductCollectionFactory $productCollectionFactory
    ) {
        parent::__construct(
            $context,
            $nodeFactory,
            $treeFactory,
            $cmsPageHelper,
            $storeManager,
            $registry,
            $categoryCollectionFactory
        );

        $this->treeFactory = $treeFactory;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param string $identifier
     * @return Node
     */
    public function getMenu(string $identifier): Node
    {
        $this->buildMenu($identifier);

        return $this->_menu;
    }

    /**
     * @param string $identifier
     */
    protected function buildMenu(string $identifier): void
    {
        $this->identifier = $identifier;

        $this->_menu = $this->_nodeFactory->create(
            [
                'data' => ['identifier' => $identifier],
                'idField' => 'root',
                'tree' => $this->treeFactory->create()
            ]
        );

        $this->initMenu();
        $this->_fillMenuTree();
    }

    /**
     * @return bool
     */
    protected function _fillMenuTree()
    {
        $collection = $this->_getMenuItemCollection()
            ->setParentIdOrder()
            ->setPositionOrder();

        if (!$collection->count()) {
            return false;
        }

        $nodes = [];
        $deferredItems = [];
        $nodes[0] = $this->_menu;

        foreach ($collection as $item) {
            if (!isset($nodes[$item->getParentId()])) {
                $deferredItems[] = $item;
                continue;
            }

            /**
             * @var $parentItemNode Node
             */
            $parentItemNode = $nodes[$item->getParentId()];

            $itemNode = $this->_nodeFactory->create(
                [
                    'data' => $item->getData(),
                    'idField' => 'item_id',
                    'tree' => $parentItemNode->getTree(),
                    'parent' => $parentItemNode
                ]
            );

            $nodes[$item->getId()] = $itemNode;
            $parentItemNode->addChild($itemNode);

            if ($categoryId = $item->getCategoryId()) {
                $this->_categoryItemIds[$item->getId()] = $categoryId;
            }

            if ($productSku = $item->getProductId()) {
                $this->_productItemsSku[$item->getId()] = $productSku;
            }
        }

        foreach ($deferredItems as $item) {
            if (!isset($nodes[$item->getParentId()])) {
                continue;
            }

            /**
             * @var $parentItemNode Node
             */
            $parentItemNode = $nodes[$item->getParentId()];

            $itemNode = $this->_nodeFactory->create(
                [
                    'data' => $item->getData(),
                    'idField' => 'item_id',
                    'tree' => $parentItemNode->getTree(),
                    'parent' => $parentItemNode
                ]
            );

            $nodes[$item->getId()] = $itemNode;
            $parentItemNode->addChild($itemNode);

            if ($categoryId = $item->getCategoryId()) {
                $this->_categoryItemIds[$item->getId()] = $categoryId;
            }

            if ($productSku = $item->getProductId()) {
                $this->_productItemsSku[$item->getId()] = $productSku;
            }
        }

        $this->_fillCategoryData($nodes);
        $this->_fillProductData($nodes);

        return true;
    }


    /**
     * @param array $nodes
     */
    protected function _fillProductData(array $nodes)
    {
        $productItemsSku = $this->_productItemsSku;

        $collection = $this->productCollectionFactory->create();

        $collection->addAttributeToSelect('url_key')
            ->addAttributeToSelect('name')
            ->addFieldToFilter('sku', ['in' => array_values($productItemsSku)]);

        /**
         * [$categoryId => $categoryData]
         */
        $productArray = $this->_prepareProductArray($collection);

        foreach ($productItemsSku as $itemId => $productSku) {
            $item = $nodes[$itemId];

            if ($productSku && isset($productArray[$productSku])) {
                $item->setProduct($productArray[$productSku]);
            }
        }
    }

    /**
     * @param $productCollection
     *
     * @return array
     */
    protected function _prepareProductArray($productCollection)
    {
        $result = [];

        foreach ($productCollection as $product) {
            $result[$product->getSku()] = $product;
        }

        return $result;
    }


    /**
     * @inheritDoc
     */
    public function initMenu()
    {
        if ($this->_menuModel) {
            return $this->_menuModel;
        }

        if ($identifier = $this->identifier) {
            $objectManager = ObjectManager::getInstance();

            $collection = $objectManager->create($this->_menuCollectionClass);
            $collection->addFieldToFilter('identifier', $identifier);
            $collection->addStoreFilter($this->_getStoresToFilter());

            $this->_menuModel = $collection->getFirstItem();
        }

        return false;
    }

    /**
     * @param Collection $menuCollection
     * @return array
     */
    public function getFormattedMenu($menuCollection): array
    {
        $result = [];

        foreach ($menuCollection as $menuItem) {
            $itemId = $menuItem->getId();

            $result[$itemId] = [
                'id' => $itemId,
                'name' => $menuItem->getTitle(),
                'css_class' => $menuItem->getItemClass(),
                'icon' => $menuItem->getIcon(),
                'icon_alt' => $menuItem->getIconAlt(),
                'open_type' => $menuItem->getOpenType(),
                'url' => $this->getFullUrl($menuItem),
                'childData' => $this->getFormattedMenu($menuItem->getChildren())
            ];
        }

        return $result;
    }

    /**
     * @param string $identifier
     * @return array
     */
    public function getMenuItems(string $identifier): array
    {
        $this->buildMenu($identifier);

        return $this->getFormattedMenu($this->_menu->getChildren());
    }

    public function getFullUrl($item)
    {
        $url = '';

        switch ($item->getUrlType()) {
            case 1:
                $url = $this->_cmsPageHelper->getPageUrl($item->getCmsPageId());

                break;

            case 2:
                $category = $item->getCategory();
                $url = $category->getUrl();

                break;
            case 3:
                $product = $item->getProduct();
                $url = $product->getProductUrl();

                break;
            default:
                $url = $item->getUrl();
        }

        if ($item->getUrlAttributes()) {
            $url .= '?' . $item->getUrlAttributes();
        }


        return $url;
    }

    public function getImageUrl($item)
    {
        if (!$item->getIcon()) {
            return null;
        }

        return $this->_urlBuilder->getDirectUrl(
            $item->getIcon(),
            ['_type' => UrlInterface::URL_TYPE_MEDIA]
        );
    }

    public function getIdentities()
    {
        if ($this->_menuModel) {
            return $this->_menuModel->getIdentities();
        }
    }
}
