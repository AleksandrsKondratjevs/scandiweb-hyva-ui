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

class FeaturedCategories extends Template implements BlockInterface
{
    protected $_template = 'Scandiweb_ContentTypes::content-type/featured-categories.phtml';

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
        $categoryIDs = [];

        $sectionData = $this->getData(('cards'));

        if (!$sectionData) {
            return [];
        }

        $sectionData = str_replace('&amp;quote;', '"', $sectionData);
        $sectionData = $this->serializer->unserialize($sectionData);

        foreach ($sectionData as $section) {
            $categoryIDs[] = (int) $section['category_id'];
        }

        $collection = $this->categoryCollection->create()->addFieldToSelect('url_path')->addFieldToFilter('entity_id', ['in' => $categoryIDs]);
        $categoryUrls = $collection->getItems();

        foreach ($sectionData as $section) {
            $categoryId = $section['category_id'];

            $data[] = [
                'title' => $section['section_title'],
                'image' => str_replace("\n", "", $section['section_img'][0]['url']),
                'url' => $this->categoryHelper->getCategoryUrl($categoryUrls[$categoryId]),
            ];
        }


        return $data;
    }
}
