<?php

/**
 * @category  Scandiweb
 * @package   Scandiweb_ContentTypes
 * @author    Baron Gobi <info@scandiweb.com>
 * @copyright Copyright (c) 2025 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\ContentTypes\Block\ContentTypes;

use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Widget\Block\BlockInterface;

class AccordionBlock extends Template implements BlockInterface
{
    protected $_template = 'Scandiweb_ContentTypes::content-type/accordion-block.phtml';

    /**
     * @var FilterProvider
     */
    private FilterProvider $filterProvider;

    /**
     * @param Context $context
     * @param FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        $this->filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    /**
     * Prepare CMS Filter Content
     *
     * @return string
     */
    public function getCmsFilterContent()
    {
        $html = $this->filterProvider->getPageFilter()->filter($this->getContent());

        $html = str_replace("&amp;", "&", $html);
        $html = str_replace("quot;", "'", $html);
        $html = str_replace("&quote;", "\"", $html);

        return $html;
    }
}
