var TeamMember = function () {
    var initTeamMemberCreateForm = function () {
        $("#profile_photo").fileinput({
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
    }
    var initTeamMemberEditForm = function () {

        var preview = [
            "no image"
        ];
        var previewCaption = "Select Profile Photo";
        var broweseLabel = "Browse";
        var showRemove = true;
        if ($("#edit_profile_photo").is("[data-profile-photo-url]")) {
            preview = [
                "<img src='" + $("#edit_profile_photo").attr("data-profile-photo-url") + "' height='180px' width='180px'>"
            ];
            previewCaption = "1 Profile photo uploaded";
            broweseLabel = "Change";
            showRemove = false;
        }
        $("#edit_profile_photo").fileinput({
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
        $("#edit_profile_photo").trigger('fileclear');
    }
    var initTeamMemberValidation = function () {
        $("#TeamMemberForm").validate({
            rules: {
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
                middle_name: {
                    maxlength: 190,
                    checkForWhiteSpace: true
                },
                profile_photo: {
                    filesize: 2048,
                    accept: "image/jpg,jpeg,png",
                    extension: "jpg|jpeg|png"
                },
                department: {
                    required: true,
                },
                designation: {
                    required: true,
                },
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
                },
                employee_id: {
                    required: true,
                    checkForWhiteSpace: true
                },
                email: {
                    required: true,
                    EMAIL: true,
                    checkForWhiteSpace: true
                },
                country_code: {
                    // required: function(element){
                    //     return $("input[name=phone_number]").val().trim()!="";
                    // },
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
                }
            },
            messages: {
                first_name: {
                    required: "First name is required.",
                    maxlength: "First Name exceeded maximum limit",
                },
                last_name: {
                    required: "Last name is required.",
                    maxlength: "Last Name exceeded maximum limit",
                },
                middle_name:{
                    maxlength: "Middle Initial exceeded maximum limit",
                },
                profile_photo: {
                    filesize: "Profile photo must be under 2 MB of size.",
                    accept: "Please select JPG, JPEG or PNG file.",
                    extension: "Profile photo must be in .jpg,.jpeg,.png format."
                },
                department: {
                    required: "Department is required."
                },
                designation: {
                    required: "Designation is required."
                },
                password: {
                    required: "Password is required.",
                    rangelength: "Password must be between 8 to 150 characters."
                },
                password_confirmation: {
                    required: "Password confirmation is required.",
                    equalTo: "Password should match.",
                },
                employee_id: {
                    required: "Employee ID is required.",
                    checkForWhiteSpace: "Space not allowed."
                },
                email: {
                    required: "Email is required.",
                    EMAIL: "Enter email address in proper format."
                },
                country_code:{
                    required: "Country code is Required.",
                    maxlength: "Country code is invalid.",
                    NumericWithPlus:"Enter a valid country code"
                },
                phone_number: {
                    required: "Phone number is required.",
                    digits: "Enter valid phone number.",
                    rangelength: "Phone number must be between 10 to 12 digits."
                }
            }
        });
    }
    return {
        init: function () {
            initTeamMemberCreateForm();
            initTeamMemberEditForm();
            initTeamMemberValidation();
        }
    };
}();
jQuery(document).ready(function () {
    TeamMember.init();
});