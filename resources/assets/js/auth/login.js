var Login = function () {

    var _login;

    var _showForm = function(form) {
        var cls = 'login-' + form + '-on';
        var form = 'login_' + form + '_form';

        _login.removeClass('login-forgot-on');
        _login.removeClass('login-signin-on');

        _login.addClass(cls);

        KTUtil.animateClass(KTUtil.getById(form), 'animate__animated animate__backInUp');
    }

    var _handleSignInForm = function() {
        
        $('#kt_login_signin_submit').on('click', function (e) {
            e.preventDefault();

        });

        // Handle forgot button
        $('#kt_login_forgot').on('click', function (e) {
            e.preventDefault();
            _showForm('forgot');
            clearForgotPasswordForm();
        });

    }
    var _handleForgotForm = function(e) {
        
        // Handle submit button
        $('#kt_login_forgot_submit').on('click', function (e) {
            e.preventDefault();

        });

        // Handle cancel button
        $('#kt_login_forgot_cancel').on('click', function (e) {
            e.preventDefault();
            _showForm('signin');
            clearLoginForm();
        });
    }
    var clearLoginForm = function () {
        AmouneeApp.resetForm($("#kt_login_signin_form"));
        // $("#kt_login_signin_form").clearForm();
        // $("#kt_login_signin_form").validate().resetForm();
    }
    var clearForgotPasswordForm = function () {
        // $("#kt_login_forgot_form").clearForm();
        // $("#kt_login_forgot_form").validate().resetForm();
        AmouneeApp.resetForm($("#kt_login_forgot_form"));
    }

    var initSignInForm = function () {

        $("#kt_login_signin_form").validate({
            rules: {
                username: {
                    required: true,
                    checkForWhiteSpace:true
                },
                password: {
                    required: true,
                    checkForWhiteSpace:true
                }
            },
            messages: {
                username: {
                    required: "Email Address is required.",
                },
                password: {
                    required: "Password is required."
                }
            }
        });
    }

    var initForgotPasswordForm = function () {
        $("#kt_login_forgot_form").validate({
            rules: {
                email: {
                    required: true,
                    EMAIL: true,
                    checkForWhiteSpace:true
                }
            },
            messages: {
                email: {
                    required: 'Recovery email address is required.',
                    EMAIL: 'Please enter valid email address.'
                }
            }
        });
    }

    var handleSignInFormSubmit = function () {

        $('#kt_login_signin_submit').click(function (e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');

            if (!form.valid()) {
                return;
            }

            AmouneeApp.disableButtonWithLoading(btn);

            $.post(form.attr("data-action"), form.serialize())
                .done(function (data) {
                    AmouneeApp.removeLoadingFromButton(btn);
                    AmouneeApp.displayResultWithCallback(data, function () {
                        window.location.href = form.attr("data-redirect-url");
                    }, function () {
                        AmouneeApp.enableButton(btn);
                    });
                }).fail(function (data) {
                    AmouneeApp.displayFailedValidation(data);
                    AmouneeApp.enableButton(btn);
                });
        });
    }

    var handleForgetPasswordFormSubmit = function () {

        $('#kt_login_forgot_submit').click(function (e) {

            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');
            if (!form.valid()) {
                return;
            }
            AmouneeApp.disableButtonWithLoading(btn);

            $.post(form.attr("data-action"), form.serialize())
                .done(function (data) {
                    AmouneeApp.displayResultWithCallback(data, function () {
                        clearForgotPasswordForm();
                        AmouneeApp.enableButton(btn);
                        _showForm('signin');
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

            _login = $('#kt_login');

            _handleSignInForm();
            _handleForgotForm();

            initSignInForm();
            initForgotPasswordForm()
            handleSignInFormSubmit();
            handleForgetPasswordFormSubmit();
        }
    };
}();

jQuery(document).ready(function () {
    Login.init();
});
