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
            title,
            sub_title,
            slider_infinitive,
            slider_show_pagination,
            slider_pagination_type_progressbar,
            slider_show_arrows,
            categories_sections,
        } = data || {};

        var attributes = {
            title,
            sub_title,
            slider_infinitive,
            slider_show_pagination,
            slider_pagination_type_progressbar,
            slider_show_arrows,
            ...(categories_sections && categories_sections.length > 0
                ? {
                      cards: JSON.stringify(categories_sections),
                  }
                : {}),
        };

        return attributes;
    };

    return BlockDirective;
});
