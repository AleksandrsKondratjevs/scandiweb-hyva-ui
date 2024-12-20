<?php

/**
 * @category  Scandiweb
 * @package   Scandiweb_StyleGuide
 * @author    Aleksandrs Kondratjevs <info@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\StyleGuide\Model;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Section
{
    private string $title;

    private string $template;

    private ?ArgumentInterface $viewModel;

    private bool $isRemoved;

    public function __construct(
        string $title,
        string $template,
        ?ArgumentInterface $viewModel = null,
        bool $isRemoved = false
    ) {
        $this->title = $title;
        $this->template = $template;
        $this->viewModel = $viewModel;
        $this->isRemoved = $isRemoved;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getViewModel(): ?ArgumentInterface
    {
        return $this->viewModel;
    }

    public function isRemoved(): bool
    {
        return $this->isRemoved;
    }
}
