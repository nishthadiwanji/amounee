var invalidEmails = [];
var AmouneeApp = function () {

    $.fn.extend({
        animateCss: function (animationName, callback) {
            var animationEnd = (function (el) {
                var animations = {
                    animation: 'animationend',
                    OAnimation: 'oAnimationEnd',
                    MozAnimation: 'mozAnimationEnd',
                    WebkitAnimation: 'webkitAnimationEnd',
                };

                for (var t in animations) {
                    if (el.style[t] !== undefined) {
                        return animations[t];
                    }
                }
            })(document.createElement('div'));

            this.addClass('animated ' + animationName).one(animationEnd, function () {
                $(this).removeClass('animated ' + animationName);

                if (typeof callback === 'function') callback();
            });

            return this;
        },
    });

    var displaySidebarSelection = function (data) {
        var pathname = window.location.href;
        pathname = pathname.replace(/\/$/, '').split('?')[0];
        $('li.menu-item a').each(function (index, element) {
            var link = $(element).attr('href');
            if (pathname == link) {
                if ($(element).parents('ul').hasClass('menu-subnav')) {
                    $(element).parent('li').addClass('menu-item-active');
                    $(element).parents('ul').parents('li').addClass('menu-item-open menu-item-here');
                }
                else{
                    $(element).parent('li').addClass('menu-item-active');
                }
            }
        });
    }

    var setSidebarPref = function () {
        $("#m_aside_left_minimize_toggle").click(function () {
            if ($(this).hasClass("m-brand__toggler--active")) {
                Cookies.set("is_sidebar_toggle", "false");
            } else {
                Cookies.set("is_sidebar_toggle", "true");
            }
        });
    }

    var setupAppDefaults = function () {

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        jQuery.validator.setDefaults({
            errorElement: 'div', //default input error message container
            errorClass: 'invalid-feedback', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ":hidden, [contenteditable='true']:not([name])",

            errorPlacement: function (error, element) { // render error placement for each input type
                var group = $(element).closest('.m-form__group-sub').length > 0 ? $(element).closest('.m-form__group-sub') : $(element).closest('.form-group');
                var help = group.find('.m-form__help');

                $(element).addClass('is-invalid');

                if (group.find('.form-control-feedback').length !== 0) {
                    return;
                }

                if (help.length > 0) {
                    help.before(error);
                } else {
                    if ($(element).closest('.input-group').length > 0) {
                        $(element).closest('.input-group').after(error);
                    } else if ($(element).hasClass("m-select2")) {
                        $(element).parent().after(error);
                    } else {
                        if ($(element).is(':checkbox')) {
                            $(element).closest('.m-checkbox').find('>span').after(error);
                        } else {
                            $(element).after(error);
                        }
                    }
                }
            },

            highlight: function (element) { // hightlight error inputs
                $(element).addClass('is-invalid');
            },

            unhighlight: function (element) { // revert the change done by hightlight
                var group = $(element).closest('.m-form__group-sub').length > 0 ? $(element).closest('.m-form__group-sub') : $(element).closest('.form-group');
                $(element).removeClass('is-invalid');
                group.find('.form-control-feedback').remove();
            },

            success: function (label, element) {
                var group = $(label).closest('.m-form__group-sub').length > 0 ? $(label).closest('.m-form__group-sub') : $(label).closest('.form-group');
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
                group.find('.form-control-feedback').remove();
            }
        });

        $('form input').keypress(function (e) {
            if (e.which == 13) {
                if ($(this).closest('form').validate().form()) {
                    if($(this).closest('form').find('.pin-submit').prop("disabled") || $(this).closest('form').find('.pin-submit').is(':disabled')){
                        return false;
                    }
                    $(this).closest('form').find('.pin-submit').click();
                }
                return false;
            }
        });

        jQuery.each(["put", "delete"], function (i, method) {
            jQuery[method] = function (url, data, callback, type) {
                if (jQuery.isFunction(data)) {
                    type = type || callback;
                    callback = data;
                    data = undefined;
                }
                return jQuery.ajax({
                    url: url,
                    type: method,
                    dataType: type,
                    data: data,
                    success: callback
                });
            };
        });
    };

    var blankFunction = function () {};

    return {
        //main function to initiate the module
        init: function () {
            displaySidebarSelection();
            setSidebarPref();
            setupAppDefaults();
        },
        resetForm: function (form) {
            form.find('select').val('').trigger('change');
            form.trigger('reset');
            form.find("select,textarea, input").removeClass('is-valid');
            form.validate().resetForm();
        },
        disableButton: function (btn) {
            btn.attr('disabled', true);
        },
        disableButtonWithLoading: function (btn) {
            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
        },
        enableButton: function (btn) {
            btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
        },
        showLoadingInButton: function (btn) {
            btn.addClass('m-loader m-loader--right m-loader--light');
        },
        removeLoadingFromButton: function (btn) {
            btn.removeClass('m-loader m-loader--right m-loader--light');
        },
        displayToastr: function (type, data, callback) {
            toastr[type](data.message, data.title, {
                timeOut: 1500,
                onHidden: function () {
                    callback();
                }
            })
        },
        displayToastrForSuccessAndFailure: function (data, success_type, success, failure) {
            if (data.result == true) {
                this.displayToastr(success_type, data, success);
            } else {
                this.displayToastr('error', data, failure);
            }
        },
        displayResultAndReload: function (data) {
            this.displayToastrForSuccessAndFailure(data, 'info', function () {
                window.location.href = window.location.href;
            }, blankFunction);
        },
        displayResultAndRedirect: function (data, url) {
            this.displayToastrForSuccessAndFailure(data, 'info', function () {
                window.location.href = url;
            }, blankFunction);
        },
        displayFailedValidation: function (data) {
            if(data.status == 422){
                $.each(data.responseJSON.errors, function (key, value) {
                    for (var i = 0; i < value.length; i++) {
                        toastr.error(value[i], 'Please Note!');
                    }
                });
            }
            else if(data.status == 401){
                toastr.error(data.responseJSON['message'], 'Attention!');
            }
            else{
                toastr.error(data.statusText, 'Attention!');
            }
        },
        displayResult: function (data) {
            this.displayToastrForSuccessAndFailure(data, 'info', blankFunction, blankFunction);
        },
        displayResultWithSuccessCallback: function (data, callback) {
            this.displayToastrForSuccessAndFailure(data, 'success', callback, blankFunction);
        },
        displayResultWithCallback: function (data, success, failure) {
            this.displayToastrForSuccessAndFailure(data, 'info', success, failure);
        },
        displayErrorMessage: function (msg) {
            toastr.error(msg, "Attention!", {
                timeOut: 2000
            });
        },
        displayResultForFailureWithCallback: function (data, success, failure) {
            if (data.result == true) {
                success()
            } else {
                this.displayToastr('error', data, failure);
            }
        },
        blockUiAndButton: function (btn, el) {
            btn.attr('disabled', 'disabled');
            App.blockUI({
                target: el,
                textOnly: true
            });
        },
        unblockUiAndButton: function (btn, el) {
            btn.removeAttr('disabled');
            App.unblockUI(el);
        },
        topFullAlert: function(result, title, message){
            $("html, body").animate({ scrollTop: 0 }, "slow");
            if (result) {
                $(".pin-alert-box").removeClass("alert-info").addClass("alert-danger");
            } else {
                $(".pin-alert-box").removeClass("alert-danger").addClass("alert-info");
            }
            $(".pin-alert-box-title").html(title);
            $(".pin-alert-box-message").html(message);
            $(".pin-alert-box").show().animateCss('slideInDown');
        }
    };
}();
$(document).ready(function () {
    AmouneeApp.init();
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body',
        trigger: 'hover'
    });
    $('select.m-select2').each(function() {
        var placeholder = $(this).data('placeholder') || 'Select an Option';
        if($(this).attr('multiple') == 'multiple'){
            $(this).select2({
                placeholder: placeholder,
                closeOnSelect: true,
                maximumSelectionLength: 10,
                language: {
                    maximumSelected: function (e) {
                        var t = "You can only select " + e.maximum + " options";
                        return t;
                    }
                },
                allowClear: true
            }); 
        }
        else{
            $(this).select2({
                placeholder: placeholder,
            }); 
        }
    });
    $('select.m-select2').change(function(){
        $(this).valid();
    });

    $(".input-daterange").datepicker({
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        endDate: '0d'
    });
    
    $(".input-daterange input").each(function(){
        $(this).datepicker({
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            endDate: '0d'
        });
    });

    $(".pin-datepicker").datepicker({
        todayHighlight: true,
        format: 'dd/mm/yyyy',
        // orientation: "bottom"
    });

    $('.pin-alert-close').click(function(){
        $(".pin-alert-box").animateCss('slideOutUp', function () {
            $(".pin-alert-box").hide();
            $(".pin-alert-title").empty();
            $(".pin-alert-message").empty();
        });
    });
});
