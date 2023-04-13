define([
    'Magento_Ui/js/form/element/single-checkbox'
], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            isMultiValueType: ['checkbox', 'multiselect'],
            featureConfigFieldInputType: 'radio',
            links: {
                featureConfigFieldInputType: '${ $.provider }:data.input_type',
            },
            listens: {
                'featureConfigFieldInputType': 'onFeatureFieldChange',
                'value': 'onValueChange'
            }
        },
        onValueChange: function (vvv) {
            if (typeof vvv == "boolean") {
                return false;
            }
            const shouldChecked = vvv === '1';
            if (typeof this.checked === 'function') {
                this.checked(shouldChecked);
            } else {
                this.checked = shouldChecked;
            }
        },
        onFeatureFieldChange: function (newValue) {
            let newPrefer = 'radio';
            if (this.isMultiValueType.includes(newValue)) {
                newPrefer = 'checkbox';
            }

            const isObservable = typeof this.prefer === 'function';
            const currentPrefer = isObservable ? this.prefer() : this.prefer;
            if (newPrefer !== currentPrefer && this.templates?.[newPrefer]) {
                if (isObservable) {
                    this.prefer(newPrefer);
                } else {
                    this.prefer = newPrefer;
                }
                if (typeof this.elementTmpl === 'function') {
                    this.elementTmpl(this.templates[newPrefer]);
                } else {
                    this.elementTmpl = this.templates[newPrefer];
                }
            }
        },
        initObservable: function () {
            this._super();
            this.observe('featureConfigFieldInputType prefer elementTmpl');
            return this;
        },
        initConfig: function () {
            this._super();

            const scope = this.dataScope.split('.');
            // this.value = scope.length > 1 ? scope.slice(1)?.[1] || null : null;
            const name = scope.length > 1 ? scope.slice(1) : scope;
            this.inputName = `${name[0]}[${this.index}][]`;
            return this;
        },

    });
});
