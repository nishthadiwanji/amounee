var Category = function () {

    var handleStoreCommission = function () {
        $("#commissionForm").validate({
            rules: {
                commission: {
                    digits: true,
                    maxlength: 191,
                    checkForWhiteSpace: true
                }
            },
            messages: {
                commission: {
                    digits: "Only digits allowed.",
                    maxlength: "Approval exceeded maximum limit of 191 characters.",
                    checkForWhiteSpace: "Blank spaces not allowed.",
                }
            }
        });

        $('button.commission-btn').click(function(){
            $('#commissionForm').attr('action', $(this).attr('data-action'));
            AmouneeApp.disableButtonWithLoading(btn);
            $.ajax({
                url: form.attr("data-action"),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    AmouneeApp.displayResultWithCallback(data, function () {
                        if(btn.data('additional-callback') != ''){
                            var additional_callback = eval(btn.data('additional-callback'));
                            if (typeof additional_callback == 'function') {
                                additional_callback(data);
                            }
                        }
                        if(form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined){
                            window.location.href = form.attr("data-redirect-url");
                        }
                        else if(btn.data('scroll') == 'off'){

                        }
                        else{ 
                            $('html, body').animate({ scrollTop: 0 }, 'slow');
                        }
                        AmouneeApp.enableButton(btn);
                    }, function () {
                        AmouneeApp.enableButton(btn);
                    });
                },
                error: function (data) {
                    AmouneeApp.displayFailedValidation(data);
                    AmouneeApp.enableButton(btn);
                }
            });
            // Disabling button with Loading
            AmouneeApp.disableButtonWithLoading(btn);
        });
    }

    var handleStoreCommissionModal = function(){

        $(document).on('click', '.commission-btn', function (e) {

            $("#commissionForm").attr("data-action",$(this).attr("data-url"));
            $("#catname").html($(this).attr("data-name"));
            $("#commissionModal").modal('show');
        });

        $('#commissionModal').on('hidden.bs.modal', function(){
            AmouneeApp.resetForm($('#commissionForm'));
        });
    }

    return {
        init: function () {
            handleStoreCommissionModal();
            handleStoreCommission();
        }
    };
}();
jQuery(document).ready(function () {
    Category.init();
});