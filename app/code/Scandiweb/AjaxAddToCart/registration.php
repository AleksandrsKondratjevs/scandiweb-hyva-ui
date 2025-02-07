<?php

/**
 * @category  Scandiweb
 * @package   Scandiweb_AjaxAddToCart
 * @author    Aleksandrs Kondratjevs <info@scandiweb.com>
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Scandiweb_AjaxAddToCart',
    __DIR__
);
