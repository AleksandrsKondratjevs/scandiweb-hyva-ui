/**
 * @category  Scandiweb
 * @package   Scandiweb_ContentTypes
 * @author    Baron Gobi <info@scandiweb.com>
 * @copyright Copyright (c) 2025 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */
define([
    "underscore",
    "Magento_PageBuilder/js/utils/object"
], function (_underscore, _object) {
    return function () {
        "use strict";

        function Attribute() {}

        /**
         * Convert value to internal format
         *
         * @param value string
         * @returns {string | object}
         */
        Attribute.prototype.fromDom = function fromDom(value) {
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
        Attribute.prototype.toDom = function toDom(name, data) {
            var content = (0, _object.get)(data, name);

            if (content && Object.keys(content).length) {
                return JSON.stringify(content);
            }

            return JSON.stringify([]);
        };

        return Attribute;
    }();
});
