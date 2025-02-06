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
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Serialize\Serializer\Json;

class FeaturedBlock extends Template implements BlockInterface
{
    protected $_template = 'Scandiweb_ContentTypes::content-type/featured-block.phtml';

    /**
     * @param Context $context
     * @param CategoryCollection $categoryCollection
     * @param CategoryListInterface $categoryList
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        protected Json $serializer,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }


    public function getImage()
    {
        $img = $this->getData('visual_content_img');

        if (!$img) {
            return null;
        }

        $img = str_replace('&amp;quote;', '"', $img);
        $img = $this->serializer->unserialize($img);

        return [
            'url' => $img[0]['url'],
            'alt' => $img[0]['name']
        ];
    }
}
