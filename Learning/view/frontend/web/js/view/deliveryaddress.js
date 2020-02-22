define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('mage.checkoutAutocomplete', {
        initContainer: function () {
            $('#checkout').on('keyup', this.keyUpHandler)
        },

        keyUpHandler: function (e) {
            if (e.target.name.indexOf('delivery]') != -1 ){
                console.log(e.target.name.indexOf('delivery]'))
                console.log(window.checkoutConfig.googlekey)
            }
        }
    });

    return $.mage.checkoutAutocomplete;
});