<?php

/**
 * @category  Scandiweb
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Scandiweb\MenuOrganizer\Model;

use ScandiPWA\MenuOrganizer\Model\Item as SourceModel;

/*
 * Class Item
 * This class is preferenced over the original class in the ScandiPWA_MenuOrganizer module
 * Reason: To add a new field (product_sku) to the Item model
 */

class Item extends SourceModel
{
    const PRODUCT_ID = 'product_sku';

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setProductId($id)
    {
        return $this->setData(self::PRODUCT_ID, $id);
    }
}
