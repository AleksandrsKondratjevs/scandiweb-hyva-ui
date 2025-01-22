/*eslint-disable */
/* jscs:disable */
define([
    "underscore",
    "Magento_PageBuilder/js/utils/object",
    "Magento_PageBuilder/js/form/provider/conditions-data-processor",
], function (_underscore, _object, conditionsDataProcessor) {
    /**
     * Copyright © Magento, Inc. All rights reserved.
     * See COPYING.txt for license details.
     */
    var Tabs = /*#__PURE__*/ (function () {
        "use strict";

        function Tabs() {}

        var _proto = Tabs.prototype;

        /**
         * Convert value to internal format
         *
         * @param value string
         * @returns {string | object}
         */
        _proto.fromDom = function fromDom(value) {
            if (value && value !== "") {
                return JSON.parse(value);
            }

            return [];
        };
        /**
         * Convert value to knockout format
         *
         * @param name string
         * @param data Object
         * @returns {string | object}
         */

        _proto.toDom = function toDom(name, data) {
            var content = (0, _object.get)(data, name);

            if (_underscore.isString(content) && content !== "") {
                content = JSON.parse(content);
            }

            if (content && Object.keys(content).length) {
                content.forEach(function (tab) {
                    if (tab.condition_option) {
                        conditionsDataProcessor(
                            tab,
                            tab["condition_option"] + "_source"
                        );
                    }
                });
                return JSON.stringify(content);
            }

            return JSON.stringify([]);
        };

        return Tabs;
    })();

    return Tabs;
});
//# sourceMappingURL=Tabs.js.map
