var TBHTable = function(){
    return {
        
        init: function (){},

        updateWithAjax: function(btn){
            
            AmouneeApp.disableButtonWithLoading(btn);
            $.post(btn.attr("data-action"))
            .done(function (data) {
                AmouneeApp.displayResultWithCallback(data, function () {
                    AmouneeApp.enableButton(btn);
                }, 
                function () {
                    AmouneeApp.enableButton(btn);
                });
            }).fail(function (data) {
                AmouneeApp.displayFailedValidation(data);
                AmouneeApp.enableButton(btn);
            });
        },
        updateWithAjaxAndRemoveRow: function(btn){
            AmouneeApp.disableButtonWithLoading(btn);
            $.post(btn.attr("data-action"))
            .done(function (data) {
                AmouneeApp.displayResultWithCallback(data, function () {
                    btn.closest("tr").fadeOut(500).promise().done(function () {
                        if ($(document).find(".pin-common-rows").length == 1) {
                            btn.closest("tr").remove();
                            window.location.href = window.location.href;
                        } 
                        else {
                            btn.parentsUntil(".pin-common-rows").parent().fadeOut(500).promise().done(function () {
                                btn.closest("tr").remove();
                            });
                        }
                    });
                }, 
                function () {
                    AmouneeApp.enableButton(btn);
                });
            }).fail(function (data) {
                AmouneeApp.displayFailedValidation(data);
                AmouneeApp.enableButton(btn);
            });
        },
        deleteRowWithAjax: function(btn){
            
            AmouneeApp.disableButtonWithLoading(btn);
            $.delete(btn.attr("data-action")).done(function (data) {
                AmouneeApp.displayResultWithCallback(data, function () {
                    btn.closest("tr").fadeOut(500).promise().done(function () {
                        if ($(document).find(".pin-common-rows").length == 1) {
                            btn.closest("tr").remove();
                            window.location.href = window.location.href;
                        } 
                        else {
                            btn.parentsUntil(".pin-common-rows").parent().fadeOut(500).promise().done(function () {
                                btn.closest("tr").remove();
                                btn.parentsUntil(".pin-common-rows").parent().remove();
                            });
                        }
                    });
                }, 
                function () {
                    AmouneeApp.enableButton(btn);
                });
            }).fail(function (data) {
                AmouneeApp.displayFailedValidation(data);
                AmouneeApp.enableButton(btn);
            });
        }

    }
}();
jQuery(document).on('click', '.pin-common-delete-row', function (e) {
    e.preventDefault();
    var btn = $(this);
    TBHTable.deleteRowWithAjax(btn);
});
jQuery(document).on('click', '.pin-common-update-row', function (e) {
    e.preventDefault();
    var btn = $(this);
    TBHTable.updateWithAjax(btn);
});
jQuery(document).on('click', '.pin-common-process-row', function (e) {
    e.preventDefault();
    var btn = $(this);
    TBHTable.updateWithAjaxAndRemoveRow(btn);
});