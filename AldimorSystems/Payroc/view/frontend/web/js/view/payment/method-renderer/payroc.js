/**
 * @category    AldimorSystems
 * @package     AldimorSystems_Payroc
 * @author      Alejandro Diaz
 * @copyright   AldimorSystems (https://aldimorsystems.com)
 */
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'jquery',
        'Magento_Checkout/js/model/quote',
        'mage/storage',
        'Magento_Payment/js/model/credit-card-validation/validator'
    ],
    function (Component, $, quote, storage) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'AldimorSystems_Payroc/payment/form',
                payrocCardNumber: '',
                payrocCvvNumber: '',
                payrocCardExpMonth: '',
                payrocCardExpYear: ''
            },

            initObservable: function () {
                this._super()
                    .observe([
                        'payrocCardNumber',
                        'payrocCvvNumber',
                        'payrocCardExpMonth',
                        'payrocCardExpYear'
                    ]);

                return this;
            },

            getMonths: function(){
                return _.map(window.checkoutConfig.payroc.months, function (value, key) {
                    return {
                        'value': key,
                        'month': value
                    };
                });
            },

            getYears: function(){
                return _.map(window.checkoutConfig.payroc.years, function (value, key) {
                    return {
                        'value': key,
                        'year': value
                    };
                });
            },

            /**
             * Prepare and process payment information
             */
            preparePayment: function () {
                var self = this;
                var customerData = quote.billingAddress._latestValue;
                var $form = $('#payroc-form');

                if ($form.validation() && $form.validation('isValid')) {

                    var data = {
                        amount: quote.totals().grand_total,
                        email: (window.checkoutConfig.quoteData.customer_email == null) ? quote.guestEmail : window.checkoutConfig.quoteData.customer_email,
                        card: {
                            name: customerData.firstname+ " " +customerData.lastname,
                            number: this.payrocCardNumber(),
                            cvv: this.payrocCvvNumber(),
                            exp_month: this.payrocCardExpMonth(),
                            exp_year: this.payrocCardExpYear()
                        },
                        address: {
                            line1: customerData.street[0],
                            line2: customerData.street[1],
                            city: customerData.city,
                            state: customerData.region,
                            postal_code: customerData.postcode
                        },
                        capture: window.checkoutConfig.payroc.capture,
                    }

                    storage.post(
                        'aldimorsystems-payroc/payment/paymentcontroller',
                        JSON.stringify(data),
                        true
                    ).done(function (data) {
                            var response = $.parseJSON(data);

                            if(response.error){
                                self.messageContainer.addErrorMessage({
                                    message: response.error.message
                                });
                            }else{
                                self.placeOrder();
                            }
                        }
                    ).error(function (message) {
                        self.messageContainer.addErrorMessage({
                            message: "Something went wrong."
                        });
                    });
                }
            }
        });
    }
);
