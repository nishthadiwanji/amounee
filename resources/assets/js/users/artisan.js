var Artisan = function () {
    
    var initArtisanCreateForm = function () {
        $("#vendor_picture").fileinput({
            showUpload: false,
            showRemove: true,
            previewFileType: 'image',
            minFileCount: 1,
            maxFileCount: 1,
            allowedFileExtensions: ['jpeg', 'jpg', 'png'],
            msgErrorClass: 'file-error-message d-none',
            minFileSize: 1,
            maxFileSize: 2000,
            msgInvalidFileExtension: 'Invalid File Format',
            msgPlaceholder: 'Select Profile Photo',
            browseLabel: 'Browse',
            browseClass: 'btn btn-sm btn-info',
            removeClass: 'btn btn-sm btn-danger',
            initialPreview: [
                "no image"
            ],
            initialCaption: "Select Profile Photo",
        });

        $("#passbook_picture").fileinput({
            showUpload: false,
            showRemove: true,
            showPreview: false,
            minFileCount: 1,
            maxFileCount: 1,
            allowedFileExtensions: ['jpeg', 'jpg', 'png'],
            msgErrorClass: 'file-error-message d-none',
            minFileSize: 1,
            maxFileSize: 2000,
            msgInvalidFileExtension: 'Invalid File Format',
            msgPlaceholder: 'Select Passbook/Cheque Book',
            browseLabel: 'Browse',
            browseClass: 'btn btn-sm btn-info',
            removeClass: 'btn btn-sm btn-danger',
            initialPreview: [
                "no image"
            ],
            initialCaption: "Select Passbook/Cheque Book",
        });
        $("#passbook_picture").change(function(){
            $(this).valid();
        });

        $("#artisan_cards").fileinput({
            showUpload: false,
            showRemove: true,
            showPreview: false,
            minFileCount: 1,
            maxFileCount: 1,
            allowedFileExtensions: ['jpeg', 'jpg', 'png'],
            msgErrorClass: 'file-error-message d-none',
            minFileSize: 1,
            maxFileSize: 2000,
            msgInvalidFileExtension: 'Invalid File Format',
            msgPlaceholder: 'Select Artisan Card',
            browseLabel: 'Browse',
            browseClass: 'btn btn-sm btn-info',
            removeClass: 'btn btn-sm btn-danger',
            initialPreview: [
                "no image"
            ],
            initialCaption: "Select Artisan Card",
        });

        // $('#has_awards').click(function(){
        //     this.checked?$('#block').show():$('#block').hide(); //time for show
        // });

        $('#has_awards').click(function(){
            if(this.checked)
            {
                $('#block').show();
            }
            else
            {
                $('#block').hide();
                $('#awards').val(" ");
            }
        });

        // $('#has_awards').click(function(){
        //    if($('#has_awards').prop('checked')==false)
        //    {
        //        $('#block').val(" ");
        //    }
        // });

        $("#id_proof").fileinput({
            showUpload: false,
            showRemove: true,
            showPreview: false,
            minFileCount: 1,
            maxFileCount: 2,
            allowedFileExtensions: ['jpeg', 'jpg', 'png'],
            msgErrorClass: 'file-error-message d-none',
            minFileSize: 1,
            maxFileSize: 2000,
            msgInvalidFileExtension: 'Invalid File Format',
            msgPlaceholder: 'Select ID Proof',
            browseLabel: 'Browse',
            browseClass: 'btn btn-sm btn-info',
            removeClass: 'btn btn-sm btn-danger',
            initialPreview: [
                "no image"
            ],
            initialCaption: "Select ID Proof",
        });
        $("#id_proof").change(function(){
            $(this).valid();
        });
    }

    var initArtisanEditForm = function () {

        var preview = [
            "no image"
        ];
        var previewCaption = "Select Profile Photo";
        var broweseLabel = "Browse";
        var showRemove = true;
        if ($("#edit_vendor_picture").is("[data-vendor-picture-url]")) {
            preview = [
                "<img src='" + $("#edit_vendor_picture").attr("data-vendor-picture-url") + "' height='180px' width='180px'>"
            ];
            previewCaption = "1 Vendor Picture uploaded";
            broweseLabel = "Change";
            showRemove = false;
        }
        $("#edit_vendor_picture").fileinput({
            showUpload: false,
            showRemove: showRemove,
            previewFileType: 'image',
            minFileCount: 1,
            maxFileCount: 1,
            allowedFileExtensions: ['jpeg', 'jpg', 'png'],
            msgErrorClass: 'file-error-message d-none',
            minFileSize: 1,
            //maxFileSize: 2000,
            msgInvalidFileExtension: 'Invalid File Format',
            browseLabel: broweseLabel,
            browseClass: 'btn btn-sm btn-info m-btn--air',
            removeClass: 'btn btn-sm btn-danger m-btn--air',
            initialPreview: preview,
            initialCaption: previewCaption
        });
        $("#edit_vendor_picture").trigger('fileclear');
    }

    var handleArtisanStore = function () 
    {     
        $("#ArtisanForm").validate({
            rules: {
                vendor_picture: {
                    filesize: 2048,
                    accept: "image/jpg,jpeg,png",
                    extension: "jpg|jpeg|png"
                },
                first_name: {
                    required: true,
                    maxlength: 190,
                    checkForWhiteSpace: true
                },
                last_name: {
                    required: true,
                    maxlength: 190,
                    checkForWhiteSpace: true
                },
                trade_name: {
                    required: true,
                    maxlength: 190,
                },
                gst: {
                    // digits: true,
                    checkForWhiteSpace: true
                },
                category_id:{
                    required: true
                },
                country_code: {
                    required: true,
                    checkForWhiteSpace: true,
                    maxlength: [4],
                    NumericWithPlus:true
                },
                phone_number: {
                    required: true,
                    checkForWhiteSpace: true,
                    digits: true,
                    rangelength: [10, 12]
                },
                email: {
                    required: true,
                    EMAIL: true,
                    checkForWhiteSpace: true
                },
                street1: {
                    required: true,
                    maxlength: 190,
                },
                street2:{
                    required: true,
                    maxlength: 190,
                },
                zip:{
                    required: true,
                    maxlength: 20,
                    digits: true,
                },
                city:{
                    required: true,
                    maxlength: 20,
                },
                state:{
                    required: true,
                    maxlength: 20,
                },
                country:{
                    required: true,
                    maxlength: 20,
                },
                'id_proof[]': {
                    filesize: 2048,
                    extension: "jpg|jpeg|png"
                },
                artisan_cards : {
                    filesize: 2048,
                    extension: "jpg|jpeg|png"
                },
                account_name:{
                    required: true,
                    maxlength: 190,
                },
                account_number:{
                    required: true,
                    maxlength: 50,
                    digits: true,
                },
                bank_name:{
                    required: true,
                    maxlength: 190,
                },
                ifsc:{
                    required: true,
                    maxlength: 30
                },
                commission:{
                    digits: true
                },
                passbook_picture : {
                    filesize: 2048,
                    extension: "jpg|jpeg|png"
                },
            },
            messages: {
                vendor_picture: {
                    filesize: "Vendor photo must be under 2 MB of size.",
                    accept: "Please select JPG, JPEG or PNG file.",
                    extension: "Vendor photo must be in .jpg,.jpeg,.png format."
                },
                category_id:{
                    required: "Category is required."
                },
                first_name: {
                    required: "First name is required.",
                    maxlength: "First Name exceeded maximum limit",
                },
                last_name: {
                    required: "Last name is required.",
                    maxlength: "Last Name exceeded maximum limit",
                },
                trade_name: {
                    required: "Trade name is required."
                },
                gst: {
                    // digits: 'Only digits allowed.'
                },
                email: {
                    required: "Email is required.",
                    EMAIL: "Enter email address in proper format."
                },
                country_code:{
                    required: "Country Code is Required.",
                    maxlength: "Country Code is invalid.",
                    NumericWithPlus:"Enter a valid country code"
                },
                phone_number: {
                    required: "Phone number is required.",
                    digits: "Enter valid phone number.",
                    rangelength: "Phone number must be between 10 to 12 digits."
                },
                street1: {
                    required: "Street 1 is required.",
                },
                street2:{
                    required: "Street 2 is required.",
                },
                zip:{
                    required: "Zip is required.",
                    digits: "Only digits allowed.",
                },
                city:{
                    required: "City name is required.",
                },
                state:{
                    required: "State name is required.",
                },
                country:{
                    required: "Country is required.",
                },
                'id_proof[]': {
                    required: "ID proof is required.",
                    filesize: "Id proof photos must be under 2 MB of size.",
                    extension: "Id proof photos must be in .jpg,.jpeg,.png format."
                },
                artisan_cards : {
                    filesize: "Artisan card must be under 2 MB of size.",
                    extension: "Artisan card must be in .jpg,.jpeg,.png format."
                },
                account_name:{
                    required: "Account Name is required.",
                },
                account_number:{
                    required: "Account Number is required.",
                    digits: "Only digits allowed."
                },
                bank_name:{
                    required: "Bank Name is required.",
                },
                ifsc:{
                    required: "IFSC is required.",
                    maxlength: "Only upto 30 characters."
                },
                commission:{
                    digits: "Only digits allowed."
                },
                passbook_picture : {
                    required: "Passbook/Cheque book picture is required.",
                    filesize: "Passbook/Cheque book photo must be under 2 MB of size.",
                    extension: "Passbook/Cheque book photo must be in .jpg,.jpeg,.png format."
                }
            }
        });

        $('#addArtisanBtn').click(function(e){
            var btn = $(this);
            var form = $(this).closest('form');
            if (!form.valid()) {
                $('#is_approved').val('0');
                return;
            }
            var formData = new FormData();
            var submitedFiles = document.getElementsByClassName('form-files');
            for (item of submitedFiles) {
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
            var totalfiles = document.getElementById('id_proof').files.length;
            for (var index = 0; index < totalfiles; index++) {
                var item = document.getElementById('id_proof');
                var extension = item.files[0].name.split('.');
                var temp2 = extension[extension.length - 1].toLowerCase();
                var size = parseFloat(item.files[0].size / 1024).toFixed(2);
                if (size > 2048) {
                    toastr.warning("Maximum upload file size is 2MB", "Size Alert");
                    return false;
                } else {
                    formData.append(item.name, document.getElementById('id_proof').files[index]);
                }
            }
            form.serializeArray().forEach(function (field) {
                if (field.value.trim() != '' || field.value.trim() != null) {
                    formData.append(field.name, field.value);
                }
            });

            // console.log(formData);
            // return false;
            // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed
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

        $('#addArtisanApproveBtn').click(function(e){
            $('#is_approved').val('1');
            $('#addArtisanBtn').trigger('click');
        });
    }

    var handleArtisanApproval = function () {
        $("#artisanApprovalForm").validate({
            rules: {
                approval_note: {
                    required: true,
                    maxlength: 191,
                    checkForWhiteSpace: true
                }
            },
            messages: {
                approval_note: {
                    required: "Approval note is required.",
                    maxlength: "Approval exceeded maximum limit of 191 characters.",
                }
            }
        });

        $('button.approve-artisan-btn').click(function(){
            $('#artisanApprovalForm').attr('action', $(this).attr('data-action'));
        });
    }

    var handleArtisanApprovalModal = function(){
        $('#approveModal').on('hidden.bs.modal', function(){
            AmouneeApp.resetForm($('#artisanApprovalForm'));
        });
    }

    var handleArtisanRejection = function () {
        $("#artisanRejectionForm").validate({
            rules: {
                rejection_note: {
                    required: true,
                    maxlength: 191,
                    checkForWhiteSpace: true
                }
            },
            messages: {
                rejection_note: {
                    required: "Rejection note is required.",
                    maxlength: "Rejection exceeded maximum limit of 191 characters.",
                }
            }
        });

        $('button.reject-artisan-btn').click(function(){
            $('#artisanRejectionForm').attr('action', $(this).attr('data-action'));
        });
    }

    var handleArtisanRejectionModal = function(){
        $('#rejectModal').on('hidden.bs.modal', function(){
            AmouneeApp.resetForm($('#artisanRejectionForm'));
        });
    }

    var handleArtisanUpdate = function() {
        $('#updateArtisanBtn').click(function(e){
        
            var btn = $(this);
            var form = $(this).closest('form');
            if (!form.valid()) {
                return;
            }
            var formData = new FormData();
            var submitedFiles = document.getElementsByClassName('form-files');
            for (item of submitedFiles) {
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
            var totalfiles = document.getElementById('id_proof').files.length;
            for (var index = 0; index < totalfiles; index++) {
                var item = document.getElementById('id_proof');
                var extension = item.files[0].name.split('.');
                var temp2 = extension[extension.length - 1].toLowerCase();
                var size = parseFloat(item.files[0].size / 1024).toFixed(2);
                if (size > 2048) {
                    toastr.warning("Maximum upload file size is 2MB", "Size Alert");
                    return false;
                } else {
                    formData.append(item.name, document.getElementById('id_proof').files[index]);
                }
            }
            form.serializeArray().forEach(function (field) {
                if (field.value.trim() != '' || field.value.trim() != null) {
                    formData.append(field.name, field.value);
                }
            });

            // console.log(formData);
            // return false;
            // Sending Request - Returned Result is then shown in Toastr - Or Failed Validations are Displayed
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
    var handleStoreImport=function(){
        $("#importForm").validate({
            rules: {
                file: {
                    required: true,
                    extension: "xls|xlsx|xlsm|csv"
                }
            },
            messages: {
                file: {
                    required: "File is required",
                    extension:"Please select Xls, Xlsx , Xlsm or CSV file"
                }
            }
        });
        $('button.import-artisan').click(function(){
            e.preventDefault();
             var form = $('#importForm');
             var btn = $(this);
             if (!form.valid()) {
                return;
            }
            AmouneeApp.disableButtonWithLoading(btn);
            var formData = new FormData();
            var files=$('#file')[0].files[0];
            formData.append('file',files);
            $.ajax({
                url: form.attr("action"),
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
        });
    }
    var handleStoreImportModal = function(){
 
        // $(document).on('click', '.import-btn', function (e) {
 
        //     $("#importForm").attr("data-action",$(this).attr("data-action"));
        //     $("#importModal").modal('show');
        // });
        $(".import-btn").click(function(){
            var btn = $(this);
            var artisan_import_url = btn.attr("data-action");
            var redirect_url = btn.attr("data-redirect-url");
            $("#importForm").attr("action",artisan_import_url);
            $("#importForm").attr("data-redirect-url",redirect_url);
        });
        $('#importModal').on('hidden.bs.modal', function(){
            AmouneeApp.resetForm($('#importForm'));
        });
    }
    return {
        init: function () {
            initArtisanCreateForm();
            initArtisanEditForm();
            handleArtisanStore();
            handleArtisanApproval();
            handleArtisanApprovalModal();
            handleArtisanRejection();
            handleArtisanRejectionModal();
            handleArtisanUpdate();
            handleStoreImport();
            handleStoreImportModal();
        }
    };
}();
jQuery(document).ready(function () {
    Artisan.init();
});