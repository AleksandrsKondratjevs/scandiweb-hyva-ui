define(["Magento_Ui/js/dynamic-rows/dynamic-rows", "underscore"], function (
    DynamicRows,
    _
) {
    "use strict";

    return DynamicRows.extend({
        /**
         * Override initHeader function
         */
        initHeader: function () {
            var labels = [],
                data;

            if (!this.labels().length) {
                _.each(
                    this.childTemplate.children,
                    function (cell) {
                        data = this.createHeaderTemplate(cell.config);

                        // Disable hiding label
                        // cell.config.labelVisible = true;

                        _.extend(data, {
                            defaultLabelVisible: data.visible(),
                            label: cell.config.label,
                            name: cell.name,
                            required: !!cell.config.validation,
                            columnsHeaderClasses:
                                cell.config.columnsHeaderClasses,
                            sortOrder: cell.config.sortOrder,
                        });
                        labels.push(data);
                    },
                    this
                );
                this.labels(_.sortBy(labels, "sortOrder"));
            }
        },
    });
});
