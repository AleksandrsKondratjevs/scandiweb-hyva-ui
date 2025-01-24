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

class FeaturedCategoriesGrid extends FeaturedCategories
{
    protected $_template = 'Scandiweb_ContentTypes::content-type/featured-categories-grid.phtml';
}
