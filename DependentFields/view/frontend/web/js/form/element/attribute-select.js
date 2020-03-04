
define([
    'uiRegistry',
    'Magento_Ui/js/form/element/select'
], function (
    uiRegistry,
    Select
) {
    'use strict';

    return Select.extend({

        defaults: {
            mapperd: []
        },

        initialize: function() {
            this._super();
            return this.setDependentOptions(this.value());
        },

        /**
         * On value change handler.
         *
         * @param {String} value
         */
        onUpdate: function (value) {
            this.setDependentOptions(value);
            return this._super();
        },

        /**
         * Set options to dependent select
         *
         * @param {String} value
         */
        setDependentOptions: function (value) {
            var options = this.mapperd['map'][value];
            var field = uiRegistry.get('index = attribute_option');
            var javidfield = uiRegistry.get('index = javid_option');
            field.setOptions(options);
            javidfield.setOptions(options);
            return this;
        }
    });
});
