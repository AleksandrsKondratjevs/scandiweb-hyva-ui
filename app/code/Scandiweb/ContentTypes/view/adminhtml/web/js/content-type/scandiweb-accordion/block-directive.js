/**
 * @category  Scandiweb
 * @package   Scandiweb_ContentTypes
 * @author    Baron Gobi <info@scandiweb.com>
 * @copyright Copyright (c) 2025 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */
define(["Scandiweb_ContentTypes/js/content-type/block-directive"], function (
    BlockDirectiveBase
) {
    "use strict";
    const $super = BlockDirectiveBase.prototype;

    function BlockDirective(parent, config, stageId) {
        BlockDirectiveBase.call(this, parent, config, stageId);
    }

    BlockDirective.prototype = Object.create($super);

    var _proto = BlockDirective.prototype;

    _proto.getAdditionalBlockAttributes = function getAdditionalBlockAttributes(
        data
    ) {
        const {
            name,
            title,
            content,
            appearance,
            active,
            show_button,
            desktop_disabled,
        } = data || {};

        var attributes = {
            name,
            title,
            content,
            appearance,
            active,
            show_button,
            desktop_disabled,
        };

        return attributes;
    };

    _proto.encodeWysiwygCharacters = function encodeWysiwygCharacters(content) {
        return content
            .replace(/\{/g, "^[")
            .replace(/\}/g, "^]")
            .replace(/"/g, "`")
            .replace(/\\/g, "|")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    };
    /**
     * @param {string} content
     * @returns {string}
     */

    return BlockDirective;
});
