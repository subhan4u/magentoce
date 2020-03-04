define([
    'jquery',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function (
    $,
    uiRegistry,
    Select,
    modal
) {
    'use strict';
    
    return Select.extend({
        initialize: function() {
            this._super();
        },
        onUpdate: function() {
            console.log(this);
            modal({
                'title': this.label,
                'autoOpen':true,
                'content':'how to pass Dynamic content'
            });
            return this._super();
        }
    });
});