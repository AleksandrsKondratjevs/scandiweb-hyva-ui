/**
 * @category  Scandiweb
 * @package   Scandiweb_ContentTypes
 * @author    Baron Gobi <info@scandiweb.com>
 * @copyright Copyright (c) 2025 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */
define([
    'jquery',
    'Magento_PageBuilder/js/content-type/text/preview',
    'Magento_PageBuilder/js/wysiwyg/factory'
], function ($, TextPreview, wysiwygFactory) {
    'use strict';

    function Preview(parent, config, stageId) {
        return TextPreview.call(this, parent, config, stageId);
    }

    Preview.prototype = Object.create(TextPreview.prototype);

    /**
     * @param {HTMLElement} element
     */
    Preview.prototype.initWysiwyg = function (element) {
        var self = this,
            wysiwygConfigData = this.config.additional_data.wysiwygConfig.wysiwygConfigData;

        this.element = element;
        element.id = this.contentType.id + '-editor';
        wysiwygConfigData.adapter.settings.fixed_toolbar_container = '.pagebuilder-content-type .pagebuilder-content-type';

        wysiwygFactory(
            this.contentType.id,
            element.id,
            this.config.name,
            wysiwygConfigData,
            this.contentType.dataStore,
            'content'
        ).then(function (wysiwyg) {
            self.wysiwyg = wysiwyg;
        });
    };

    return Preview;
});
