define([
    'Magento_Ui/js/form/components/button',
    'underscore'
], function (Button, _) {
    'use strict';

    return Button.extend({
        defaults: {
            entityId: null,
            featureId: null
        },

        /**
         * Apply action on target component,
         * but previously create this component from template if it is not existed
         *
         * @param {Object} action - action configuration
         */
        applyAction: function (action) {
            if (action.params && action.params[0]) {
                action.params[0]['field_id'] = this.entityId;
                action.params[0]['feature_id'] = this.featureId;
            } else {
                action.params = [{
                    'field_id': this.entityId,
                    'feature_id': this.featureId
                }];
            }

            this._super();
        }
    });
});
