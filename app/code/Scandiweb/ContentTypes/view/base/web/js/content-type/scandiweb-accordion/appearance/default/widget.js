/**
 * @category  Scandiweb
 * @package   Scandiweb_ContentTypes
 * @author    Baron Gobi <info@scandiweb.com>
 * @copyright Copyright (c) 2025 Scandiweb, Inc (https://scandiweb.com)
 * @license   http://opensource.org/licenses/OSL-3.0 The Open Software License 3.0 (OSL-3.0)
 */
define([
    'jquery',
    'mage/accordion'
], function ($) {
    'use strict';

    return function (config, element) {
        var accordionElement = $(element),
            hashIndex = window.location.href.indexOf('#'),
            anchor = accordionElement.data('accordion-anchor') || false,
            isAnchored = (hashIndex !== -1 && anchor && anchor === window.location.href.substr(hashIndex + 1));

        accordionElement.accordion({
            active: accordionElement.data('accordion-active') || isAnchored ? [0] : [],
            collapsible: true,
            multipleCollapsible: true
        });

        if (isAnchored) {
            $('html, body').animate({
                scrollTop: accordionElement.offset().top
            }, 500);
        }
    };
});
