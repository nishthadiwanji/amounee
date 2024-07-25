var TBHForm = function(){

    return {
        
        init: function (){

        },

        // Here we have given an advanced option, if they want to redirect then they just have to add the data-redirect-url
        // In the form, else if they keep it empty this will just scroll up to the top of the page
        // This we will do when we are giving demo to the client

        commonPostFormSubmit: function(form, btn){

            // Checking whether form is valid for submission 

            if (!form.valid()) {
                return;
            }

            // Disabling button with Loading

            AmouneeApp.disableButtonWithLoading(btn);

            // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

            $.post(form.attr("action"), form.serialize())
                .done(function (data) {
                    AmouneeApp.displayResultWithCallback(data, function(){
                        if(btn.data('additional-callback')){
                            var additional_callback = eval(btn.data('additional-callback'));
                            if (typeof additional_callback == 'function') {
                                additional_callback(data);
                            }
                        }
                        if(form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined){
                            window.location.href = form.attr("data-redirect-url");
                        }
                        else{
                            AmouneeApp.resetForm(form);
                            $('html, body').animate({ scrollTop: 0 }, 'slow');
                        }
                        AmouneeApp.enableButton(btn);
                    },function(){
                        AmouneeApp.enableButton(btn);
                    });
                }).fail(function (data) {
                    AmouneeApp.displayFailedValidation(data);
                    AmouneeApp.enableButton(btn);
                });
        },

        commonUpdateFormSubmit: function(form, btn){

            // Checking whether form is valid for submission 

            if (!form.valid()) {
                return;
            }

            // Disabling button with Loading

            AmouneeApp.disableButtonWithLoading(btn);

            // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

            $.put(form.attr("action"), form.serialize())
                .done(function (data) {
                    AmouneeApp.displayResultWithCallback(data,function(){
                        if(btn.data('additional-callback')){
                            var additional_callback = eval(btn.data('additional-callback'));
                            if (typeof additional_callback == 'function') {
                                additional_callback(data);
                            }
                        }
                        if(form.attr("data-redirect-url") != '' && form.attr("data-redirect-url") != null && form.attr("data-redirect-url") != undefined){
                            window.location.href = form.attr("data-redirect-url");
                        }
                        else{
                            $('html, body').animate({ scrollTop: 0 }, 'slow');
                        }
                        AmouneeApp.enableButton(btn);
                    },function(){
                        AmouneeApp.enableButton(btn);
                    });
                }).fail(function (data) {
                    AmouneeApp.displayFailedValidation(data);
                    AmouneeApp.enableButton(btn);
                });
        },
        multifileFormSubmit: function(form, btn, method){
            
            // Checking whether form is valid for submission 

            if (!form.valid()) {
                return;
            }

            // Disabling button with Loading

            AmouneeApp.disableButtonWithLoading(btn);

            // Adding files and serializing the form data
            
            var formData = new FormData();
            var submitedFiles = document.getElementsByClassName('form-files');
            for(item of submitedFiles){
                if (item.files && item.files[0]) {
                    var extension = item.files[0].name.split('.');
                    var temp2 = extension[extension.length - 1].toLowerCase();
                    var size = parseFloat(item.files[0].size / 1024).toFixed(2);
                    if (size > 2048) {
                        toastr.warning("Maximum upload file size is 2MB", "Size Alert");
                        return false;
                    } else {
                        formData.append(item.name, item.files[0]);
                    }
                }
            }
            // Here we might face an issue when doing Multiple Select.
            // Please make sure to retest this when you are using it in a form with Multiple Selection and File Upload
            // The OR operator written here can be updated to AND operator
            // Make sure that the server side code can handle NULL values before doing that
            // The current system is already built and this code is better not altered
            form.serializeArray().forEach(function (field) {
                if (field.value.trim() != '' || field.value.trim() != null) {
                    formData.append(field.name, field.value);
                }
            });
            
            // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed

            $.ajax({
                url: form.attr("data-action"),
                type: method,
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
        }

    }
}();

// The following function is used to reduce writing same functions to submit forms in our system

$(document).on('click', '.pin-common-submit', function (e){
    e.preventDefault();

    // The below lines are only for understanding for anyone using this library further

    var btn = $(this);
    var form = $(this).closest('form');

    // Main function is called

    TBHForm.commonPostFormSubmit(form, btn);
});

// The following function is used to reduce writing same functions to update our forms in the system

$(document).on('click', '.pin-common-update', function (e){
    e.preventDefault();

    // The below lines are only for understanding for anyone using this library further

    var btn = $(this);
    var form = $(this).closest('form');

    // Main function is called

    TBHForm.commonUpdateFormSubmit(form, btn);
});
// The following function is used to reduce writing same functions to reset forms in our system

$(document).on('click', '.pin-common-reset', function (e){
    e.preventDefault();

    // The below lines are only for understanding for anyone using this library further

    var form = $(this).closest('form');

    // Main function is called

    AmouneeApp.resetForm(form);
    $('html, body').animate({ scrollTop: 0 }, 'slow');

});
// The following function is used to reduce writing the form submitting function in our system which includes multipart forms

$(document).on('click', '.pin-fwfile-submit', function(e){
    e.preventDefault();

    // The below lines are only for understanding for anyone using this library further

    var btn = $(this);
    var form = $(this).closest('form');

    // Main function is called

    TBHForm.multifileFormSubmit(form, btn, 'POST');
});

$(document).on('click', '.pin-fwfile-update', function(e){
    e.preventDefault();

    // The below lines are only for understanding for anyone using this library further

    var btn = $(this);
    var form = $(this).closest('form');

    // Main function is called

    TBHForm.multifileFormSubmit(form, btn, 'POST');
});

