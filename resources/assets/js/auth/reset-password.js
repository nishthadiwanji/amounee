var resetPassword = function () {

    var initResetPasswordForm = function(){

        $("#resetPasswordForm").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8,
                    checkForWhiteSpace:true
                },
                password_confirmation: {
                    required: true,
                    equalTo:'#password',
                    checkForWhiteSpace:true
                },
            },
            messages: {
                password: {
                    required: "New password is required.",
                    minlength:"Minimum length of password is 8"
                },
                password_confirmation: {
                    required: "Password confirmation is required",
                    equalTo:"Password should match",
                }
            }
        });
    }

    return {
        init: function () {
            initResetPasswordForm();
        }
    };
}();

jQuery(document).ready(function () {
    resetPassword.init();
});
