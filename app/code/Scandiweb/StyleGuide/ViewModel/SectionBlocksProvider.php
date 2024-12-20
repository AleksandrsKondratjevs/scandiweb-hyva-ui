<?php

/**
 * @category  Scandiweb
 * @package   Scandiweb_StyleGuide
 * @author    Aleksandrs Kondratjevs <info@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\StyleGuide\ViewModel;

use Generator;
use InvalidArgumentException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Scandiweb\StyleGuide\Block\Section as SectionBlock;
use Scandiweb\StyleGuide\Block\SectionFactory as SectionBlockFactory;
use Scandiweb\StyleGuide\Model\Section;

class SectionBlocksProvider implements ArgumentInterface
{
    private SectionBlockFactory $sectionBlockFactory;

    /**
     * @var Section[]
     */
    private array $sections;

    public function __construct(SectionBlockFactory $sectionBlockFactory, array $sections = [])
    {
        foreach ($sections as $section) {
            if (!$section instanceof Section) {
                throw new InvalidArgumentException('Array of section models required.');
            }
        }
        $this->sections = $sections;
        $this->sectionBlockFactory = $sectionBlockFactory;
    }

    public function getSectionBlocks(): Generator
    {
        foreach ($this->sections as $section) {
            if ($section->isRemoved()) {
                continue;
            }
            /** @var SectionBlock $block */
            $block = $this->sectionBlockFactory->create();
            $block->setTemplate($section->getTemplate());
            $block->setTitle($section->getTitle());
            $block->setViewModel($section->getViewModel());
            yield $block;
        }
    }
}
