define(['jquery'], function ($) {
    'use strict';

    return function (Component) {
        return {
            ...Component, 
            options: {
                ...Component.options,
                productSku: '.field-product_sku',
            },
            _toggleSelector: function() {
                var activeSelector = null, self = this;
    
                switch (parseInt(self.activeUrlType)) {
                    case 0:
                        activeSelector = self.options.customUrl;
                        break;
                    case 1:
                        activeSelector = self.options.cmsPageSelect;
                        break;
                    case 2:
                        activeSelector = self.options.categorySelect;
                        break;
                    case 3:
                        activeSelector = self.options.productSku;
                        break;
                    default:
                        activeSelector = self.options.customUrl;
                        break;
                }
    
                $.each(this.options, function (index, element) {
                    self.disableElement($(element));
                });
    
                self.enableElement($(activeSelector));
            },
        };
    }
});