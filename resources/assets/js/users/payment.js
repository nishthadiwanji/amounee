var Payment = function () {
    var initPaymentValidation = function () {
        $("#PaymentForm").validate({
            rules: {
                artisan_id: {
                    required: true
                },
                payment_amount: {
                    required: true,
                    validNumber: true,
                    greater_than_zero: true,
                    checkForWhiteSpace: true
                },
                payment_type: {
                    required: true,
                    maxlength: 190,
                },
                paid_amount: {
                    validNumber: true,
                    greater_than_zero: true,
                    equalTo: "#payment_amount",
                    checkForWhiteSpace: true
                },
                note: {
                    maxlength: 500
                }
            },
            messages: {
                artisan_id: {
                    required: "Artisan is required."
                },
                payment_amount: {
                    required: "Payment amount is required.",
                    digits: "Only digits allowed.",
                    checkForWhiteSpace: "Spaces are not allowed."
                },
                payment_type: {
                    required: "Payment type is required.",
                },
                paid_amount: {
                    digits: "Only digits allowed.",
                    equalTo: 'Paid amount should be equal to payment amount.'
                },
                note: {
                    maxlength: "Limit reached."
                }
            }
        });
    }
    return {
        init: function () {
            initPaymentValidation();
        }
    };
}();
jQuery(document).ready(function () {
    Payment.init();
});