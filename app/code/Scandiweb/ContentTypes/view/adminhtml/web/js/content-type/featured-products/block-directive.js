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
            tabs = [],
            title,
            sub_title,
            is_show_all_enabled,
            slider_infinitive,
            slider_show_pagination,
            slider_pagination_type_progressbar,
            slider_show_arrows,
        } = data || {};

        const sortedTabs = tabs.sort(
            (a, b) => a.tab_sort_order - b.tab_sort_order
        );

        const formattedTabs = sortedTabs.map((tab) => {
            const tabResult = {};
            tabResult.products_count = tab.products_count;
            tabResult.sort_order = tab.sort_order;
            tabResult.condition_option = tab.condition_option || "condition";
            tabResult.url = tab.url;
            tabResult.title = tab.title;
            tabResult.category_id = tab.category_ids;
            tabResult.sku = tab.sku;

            if (tabResult.condition_option === "condition") {
                tabResult.conditions = this.encodeWysiwygCharacters(
                    tab.conditions_encoded || ""
                );
            }
            return tabResult;
        });

        var attributes = {
            title,
            sub_title,
            is_show_all_enabled,
            slider_infinitive,
            slider_show_pagination,
            slider_pagination_type_progressbar,
            slider_show_arrows,
            tabs: JSON.stringify(formattedTabs),
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
