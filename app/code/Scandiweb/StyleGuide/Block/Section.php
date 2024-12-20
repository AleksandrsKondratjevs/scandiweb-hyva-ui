<?php

/**
 * @category  Scandiweb
 * @package   Scandiweb_StyleGuide
 * @author    Aleksandrs Kondratjevs <info@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\StyleGuide\Block;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;

class Section extends Template
{
    public function getTitle(): string
    {
        return $this->getData('title') ?? '';
    }

    public function setTitle(string $title): void
    {
        $this->setData('title', $title);
    }

    public function getViewModel(): ?ArgumentInterface
    {
        return $this->getData('view_model');
    }

    public function setViewModel(?ArgumentInterface $viewModel)
    {
        return $this->setData('view_model', $viewModel);
    }
}
