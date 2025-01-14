define([
    "Scandiweb_ContentTypes/js/content-type/block-directive",
    "Scandiweb_ContentTypes/js/helper/link",
], function (BlockDirectiveBase) {
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
            title,
            is_show_all_enabled,
            slider_infinitive,
            slider_show_pagination,
            slider_pagination_type_progressbar,
            slider_show_arrows,
            products_sections,
        } = data || {};

        var attributes = {
            title,
            is_show_all_enabled,
            slider_infinitive,
            slider_show_pagination,
            slider_pagination_type_progressbar,
            slider_show_arrows,
            ...(products_sections && products_sections.length > 0
                ? {
                      cards: JSON.stringify(products_sections),
                  }
                : {}),
        };

        return attributes;
    };

    return BlockDirective;
});
