var Status = function () {

    var handleApproveReq = function () {

        $(document).on('click', '.pin-approve', function (e) {
            e.preventDefault();
            
            var btn = $(this);
            btn.tooltip('hide');
            AmouneeApp.disableButton(btn);

            $.post(btn.attr("data-action"))
                .done(function (data) {
                    TBHList.listButtonAjaxResultWithToastr(data, btn);
                }).fail(function (data) {
                    AmouneeApp.displayFailedValidation(data);
                    AmouneeApp.enableButton(btn);
                });
        });
    }

    var handleRejectReq = function () {

        $(document).on('click', '.pin-reject', function(e) {
            e.preventDefault();

            var btn = $(this);
            btn.tooltip('hide');
            AmouneeApp.disableButton(btn);

            $.post(btn.attr("data-action"))
                .done(function (data) {
                    TBHList.listButtonAjaxResultWithToastr(data, btn);
                }).fail(function (data) {
                    AmouneeApp.displayFailedValidation(data);
                    AmouneeApp.enableButton(btn);
                });
        });
    }

    return {
        init: function () {
            handleApproveReq();
            handleRejectReq();
        }
    };
}();

jQuery(document).ready(function () {
    Status.init();
});
