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
use Magento\Catalog\Model\Product\Attribute\Repository as ProductAttributeRepository;
use Magento\Catalog\Model\Category;
use Magento\Framework\View\Result\PageFactory;

class GetFeaturedProducts extends Action
{
    public const DEFAULT_PRODUCTS_LIMIT = '12';

    protected PageFactory $_pageFactory;

    /**
     * @param Context $context
     * @param ProductAttributeRepository $productAttributeRepository
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        Context $context,
        ResultFactory $resultFactory,
        PageFactory $pageFactory,
        protected ProductAttributeRepository $productAttributeRepository,
    ) {
        $this->_pageFactory = $pageFactory;
        $this->resultFactory = $resultFactory;
        parent::__construct($context);
    }

    /**
     * @return void
     */
    public function execute()
    {
        $productsLimit = $this->getRequest()->getParam('products_limit') ?? self::DEFAULT_PRODUCTS_LIMIT;
        $page = $this->_pageFactory->create();
        $categoryId = $this->getRequest()->getParam('category_id');
        $isSliderInfinitive = $this->getRequest()->getParam('is_slider_infinitive');
        $isSliderShowPagination = $this->getRequest()->getParam('is_slider_show_pagination');
        $isSliderPaginationProgressbar = $this->getRequest()->getParam('is_slider_pagination_progressbar');
        $isSliderShowArrows = $this->getRequest()->getParam('is_slider_show_arrows');

        $sliderHtml = $page->getLayout()->createBlock(Template::class)
            ->setTemplate('Magento_Catalog::product/slider/product-slider.phtml')
            ->setData('category_ids', (string)$categoryId)
            ->setData('page_size', (string) $productsLimit)
            ->setData('sort_attribute', 'position')
            ->setData('sort_direction', 'ASC')
            ->setData('is_slider_infinitive', $isSliderInfinitive)
            ->setData('is_slider_show_pagination', $isSliderShowPagination)
            ->setData('is_slider_pagination_progressbar', $isSliderPaginationProgressbar)
            ->setData('is_slider_show_arrows', $isSliderShowArrows)
            ->toHtml();

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
        $cacheTags[] = Category::CACHE_TAG . '_' . $categoryId;
        $cacheTags  = implode(', ', $cacheTags);

        $this->getResponse()->setHeader('X-Magento-Tags', $cacheTags, true);
    }
}
