var Authorization = function () {

    var handleUserBan = function () {

        $(document).on('click', '.pin-ban-user', function (e) {
            e.preventDefault();
            
            var btn = $(this);
            btn.tooltip('hide');
            AmouneeApp.disableButton(btn);

            $.delete(btn.attr("data-action"))
                .done(function (data) {
                    TBHList.listButtonAjaxResultWithToastr(data, btn);
                }).fail(function (data) {
                    AmouneeApp.displayFailedValidation(data);
                    AmouneeApp.enableButton(btn);
                });
        });
    }

    var handleUserUnban = function () {

        $(document).on('click', '.pin-unban-user', function(e) {
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
            handleUserBan();
            handleUserUnban();
        }
    };
}();

jQuery(document).ready(function () {
    Authorization.init();
});
