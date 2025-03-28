/**
 * @category  Scandiweb
 * @package   Scandiweb_ContentTypes
 * @author    Baron Gobi <info@scandiweb.com>
 * @copyright Copyright (c) 2025 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */
define([
    'Magento_Ui/js/dynamic-rows/dynamic-rows',
], function (dynamicRows) {
    'use strict';

    return dynamicRows.extend({
        /**
         * Init default record
         *
         * @returns Chainable.
         */
        initDefaultRecord: function () {
            if (this.inputVariations && !this.recordData().length) {
                this.recordData(Object.entries(this.inputVariations).map(
                    ([key, label], index) => {
                        return {
                            record_id: index,
                            label: label,
                            value: ""
                        }
                    }));
                this.reload();
            }

            return this;
        }
    });
});
