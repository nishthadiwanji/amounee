var ChangePassword = function () {

    var initChangePassword = function(){

        $("#changePasswordForm").validate({
            rules: {
				current_password:{
                    required: true,
                    checkForWhiteSpace:true
                },
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
                current_password:{
                    required:"Current password is required."
                },
                password: {
                    required: "New password is required.",
                    minlength:"New password must be 8 characters long."
                },
                password_confirmation: {
                    required: "Password confirmation is required.",
                    equalTo:"Password should match.",
                }
			}
        });
    }

    return {
        init: function () {
            initChangePassword();
        }
    };
}();

jQuery(document).ready(function () {
    ChangePassword.init();
});
