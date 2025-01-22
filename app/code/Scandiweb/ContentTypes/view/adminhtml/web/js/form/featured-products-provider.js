/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    "Magento_Ui/js/form/provider",
    "Magento_PageBuilder/js/events",
    "Magento_PageBuilder/js/form/provider/conditions-data-processor",
], function (Provider, events, conditionsDataProcessor) {
    "use strict";

    return Provider.extend({
        /** @inheritdoc **/
        initClient: function () {
            return this;
        },

        /** @inheritdoc **/
        save: function () {
            events.trigger("featuredProducts:saveAfter", this.get("data"));

            var data = this.get("data");

            conditionsDataProcessor(data, data["condition_option"] + "_source");

            return this;
        },
    });
});
