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
        const { title, sub_title, faq_sections = [] } = data || {};

        const sortedTabs = faq_sections.sort(
            (a, b) =>
                Number(a.section_sort_order) - Number(b.section_sort_order)
        );

        var attributes = {
            title,
            sub_title,
            ...(sortedTabs && sortedTabs.length > 0
                ? {
                      cards: JSON.stringify(sortedTabs),
                  }
                : {}),
        };

        return attributes;
    };

    return BlockDirective;
});
