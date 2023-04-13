define([
    'Magento_Ui/js/form/components/fieldset',
], function (Fieldset) {
    'use strict';

    return Fieldset.extend({
        defaults: {
            canShowDynamicRowsInputType: ['dropdown', 'checkbox', 'radio', 'multiselect'],
            links: {
                featureConfigFieldInputType: '${ $.provider }:data.input_type',
            },
            listens: {
                'featureConfigFieldInputType': 'onFeatureFieldChange'
            }
        },
        onFeatureFieldChange: function (newInputType) {
            const shouldVisible = this.canShowDynamicRowsInputType.includes(newInputType);
            this.visible(shouldVisible);
        }
    });
});
