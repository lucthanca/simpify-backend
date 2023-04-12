define([
    'jquery',
    'Magento_Ui/js/form/components/insert-form',
    'SimiCart_SimpifyManagement/js/notification'
], function ($, Insert, bssNotification) {
    'use strict';

    return Insert.extend({
        defaults: {
            listens: {
                responseData: 'onResponse'
            },
            modules: {
                fieldListing: '${ $.configFieldListingProvider }',
                fieldModal: '${ $.configFieldModalProvider }'
            }
        },

        /**
         * Close modal, reload sub-user listing
         *
         * @param {Object} responseData
         */
        onResponse: function (responseData) {
            if (responseData.success) {
                this.fieldModal().closeModal();
                this.fieldListing().reload({
                    refresh: true
                });
                bssNotification.bssNotification(responseData);
            }
        },

        /**
         * Event method that closes "Edit" modal and refreshes grid after sub-user
         * was removed through "Delete" button on the "Edit" modal
         */
        onSubUserDelete: function (responseData) {
            this.fieldModal().closeModal();
            this.fieldListing().reload({
                refresh: true
            });
            bssNotification.bssNotification(responseData);
        }
    });
});
