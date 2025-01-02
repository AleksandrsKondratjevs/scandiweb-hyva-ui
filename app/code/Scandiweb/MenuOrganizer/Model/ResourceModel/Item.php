<?php

/**
 * @category  Scandiweb
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\MenuOrganizer\Model\ResourceModel;

use ScandiPWA\MenuOrganizer\Model\ResourceModel\Item as SourceItem;

/*
 * Class Item
 * This class is preferenced over the original class in the ScandiPWA_MenuOrganizer module
 * Reason: To add a new field (product_sku) to the Item model
 */

class Item extends SourceItem
{
    /**
     * @param $object
     */
    protected function _processUrlSave($object)
    {
        switch ($object->getUrlType()) {
            case 0:
            default:
                $object->setData('cms_page_id', null);
                $object->setData('category_id', null);
                $object->setData('product_sku', null);
                break;
            case 1:
                $object->setData('url', null);
                $object->setData('category_id', null);
                $object->setData('product_sku', null);
                break;
            case 2:
                $object->setData('url', null);
                $object->setData('cms_page_id', null);
                $object->setData('product_sku', null);
                break;
            case 3:
                $object->setData('url', null);
                $object->setData('cms_page_id', null);
                $object->setData('category_id', null);
                break;
        }
    }
}
