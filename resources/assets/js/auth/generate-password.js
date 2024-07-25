var GeneratePassword = function () {

    var openGeneratePasswordModal = function () {

        $(document).on('click', '.pin-generate-password', function (e) {

            $("#generatePasswordForm").attr("data-action",$(this).attr("data-url"));
            $("#username").html($(this).attr("data-name"));
            $("#generatePasswordModal").modal('show');
        });

        $("#generatePasswordModal").on('hidden.bs.modal', function () {
            // $("#generatePasswordForm")[0].reset();
            // $("#generatePasswordForm").validate().resetForm();
            AmouneeApp.resetForm($("#generatePasswordForm"));
        });
    }

    var handleGeneratePassword = function () {
         $("#generatePasswordForm").validate({
            rules: {
                password: {
                    required: true,
                    checkForWhiteSpace: true,
                    spaceNotAllowed: true,
                    rangelength: [8, 150]
                },
                password_confirmation: {
                    required: true,
                    equalTo: '#password',
                    checkForWhiteSpace: true
                }
            },
            messages: {
                password: {
                    required: "Password is required.",
                    rangelength: "Password must be between 8 to 150 characters."
                },
                password_confirmation: {
                    required: "Password confirmation is required.",
                    equalTo: "Password should match."
                }
            }
        });

        $('#generatePasswordBtn').click(function (e) {
            e.preventDefault();
            var btn = $(this);
            var form = $("#generatePasswordForm");

            if (!form.valid()) {
                return;
            }

            AmouneeApp.disableButtonWithLoading(btn);

            $.post(form.attr("data-action"), form.serialize())
                .done(function (data) {
                    AmouneeApp.displayResultWithCallback(data, function () {
                        AmouneeApp.enableButton(btn);
                        // $("#generatePasswordModal").modal("toggle");
                        window.location.href = window.location.href;
                    }, function () {
                        AmouneeApp.enableButton(btn);
                    });
                }).fail(function (data) {
                    AmouneeApp.displayFailedValidation(data);
                    AmouneeApp.enableButton(btn);
                });
        });
    }

    return {
        init: function () {
            openGeneratePasswordModal();
            handleGeneratePassword();
        }
    };
}();

jQuery(document).ready(function () {
    GeneratePassword.init();
});
