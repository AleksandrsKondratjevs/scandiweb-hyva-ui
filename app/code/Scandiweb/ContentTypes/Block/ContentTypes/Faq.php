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
use Magento\Framework\Serialize\Serializer\Json;

class Faq extends Template implements BlockInterface
{
    protected $_template = 'Scandiweb_ContentTypes::content-type/faq.phtml';


    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        protected Json $serializer,
        array $data = []
    ) {

        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getSections(): array
    {
        $sectionData = $this->getData(('cards'));

        if (!$sectionData) {
            return [];
        }

        $sectionData = str_replace('&amp;quote;', '"', $sectionData);
        $sectionData = $this->serializer->unserialize($sectionData);

        return $sectionData;
    }
}
