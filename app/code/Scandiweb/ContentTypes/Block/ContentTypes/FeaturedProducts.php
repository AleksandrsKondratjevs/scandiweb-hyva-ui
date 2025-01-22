<?php

/**
 * @category  Scandiweb
 * @package   Scandiweb_ContentTypes
 * @author    Aleksandrs Kondratjevs <info@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\ContentTypes\Block\ContentTypes;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Helper\Category as CategoryHelper;

class FeaturedProducts extends Template implements BlockInterface
{
    protected $_template = 'Scandiweb_ContentTypes::content-type/featured-products.phtml';

    /**
     * @var CategoryHelper
     */
    protected $categoryHelper;

    /**
     * @param Context $context
     * @param CategoryCollection $categoryCollection
     * @param CategoryListInterface $categoryList
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        protected CategoryCollection $categoryCollection,
        CategoryHelper $categoryHelper,
        protected Json $serializer,
        array $data = []
    ) {
        $this->categoryCollection = $categoryCollection;
        $this->categoryHelper = $categoryHelper;

        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getSections(): array
    {
        $data = [];

        $tabsData = $this->getData(('tabs'));

        if (!$tabsData) {
            return [];
        }

        $tabsData = str_replace('&amp;quote;', '"', $tabsData);
        $tabsData = $this->serializer->unserialize($tabsData);

        foreach ($tabsData as $section) {
            $data[] = [
                'title' => $section['title'],
                'url' => $section['url'],
                'limit' => $section['products_count'],
                'condition_option' => $section['condition_option'],
                'sort_order' => $section['sort_order'],
                'category_id' => isset($section['category_id']) ? $section['category_id'] : null,
                'sku' =>  isset($section['sku']) ? $section['sku'] : null,
                'conditions' =>  isset($section['conditions']) ? $section['conditions'] : null,
            ];
        }


        return $data;
    }
}
