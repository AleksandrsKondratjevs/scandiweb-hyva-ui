<?php

/**
 * @category  Scandiweb
 * @package   Scandiweb_StyleGuide
 * @author    Aleksandrs Kondratjevs <info@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\StyleGuide\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Theme\Block\Html\Breadcrumbs;

class Index extends Action implements HttpGetActionInterface
{
    private PageFactory $pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set(__('Style Guide'));

        /** @var Breadcrumbs */
        $breadcrumbs = $page->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb(
            'home',
            [
                'label' => __('Home'),
                'title' => __('Home'),
                'link' => $this->_url->getUrl('')
            ]
        );
        $breadcrumbs->addCrumb(
            'level_1',
            [
                'label' => __('Level 1'),
                'title' => __('Level 1'),
                'link' => '#'
            ]
        );
        $breadcrumbs->addCrumb(
            'level_2',
            [
                'label' => __('Level 2'),
                'title' => __('Level 2'),
                'link' => '#'
            ]
        );
        $breadcrumbs->addCrumb(
            'level_3',
            [
                'label' => __('Level 3'),
                'title' => __('Level 3'),
                'link' => '#'
            ]
        );
        $breadcrumbs->addCrumb(
            'level_4',
            [
                'label' => __('Level 4'),
                'title' => __('Level 4'),
                'link' => '#'
            ]
        );
        $breadcrumbs->addCrumb(
            'style_guide',
            [
                'label' => __('Style Guide'),
                'title' => __('Style Guide')
            ]
        );

        return $page;
    }
}
