<?php

/**
 * @category  Scandiweb
 * @copyright Copyright (c) 2024 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */

namespace Scandiweb\MenuOrganizer\Block\Adminhtml\Item\Edit;

use ScandiPWA\MenuOrganizer\Block\Adminhtml\Item\Edit\Form as SourceForm;

/**
 * Adminhtml menu edit form
 *
 * Class Form
 * Overriding the original class in the ScandiPWA_MenuOrganizer module
 * Reason: To add a new url type (product) field to the Item form
 */
class Form extends SourceForm
{
    /**
     * @param $fieldset
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addCategoryField(\Magento\Framework\Data\Form\Element\Fieldset $fieldset)
    {
        $fieldset->addField(
            'category_id',
            'select',
            [
                'label' => __('Category'),
                'title' => __('Category'),
                'values' => $this->_menumanagerHelper->getCategoriesAsOptions(true),
                'name' => 'category_id',
                'required' => true
            ]
        );

        //vvvv Add new product field vvvv
        $fieldset->addField(
            'product_sku',
            'text',
            [
                'name' => 'product_sku',
                'label' => __('Product Sku'),
                'title' => __('Product Sku'),
                'required' => true,
            ]
        );
    }
}
