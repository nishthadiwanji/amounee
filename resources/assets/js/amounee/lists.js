var TBHList = function(){
    return {
        
        init: function (){

        },

        listButtonAjaxResultWithToastr: function(data, btn){
            AmouneeApp.displayResultWithCallback(data, function () {
                if ($(document).find(".pin-list-item").length == 1) {
                    window.location.href = window.location.href;
                } else {
                    btn.parentsUntil(".pin-list-item").parent().fadeOut(500)
                        .promise().done(function () {
                            btn.parentsUntil(".pin-list-item").parent().remove();
                        });
                }
            }, function () {
                AmouneeApp.enableButton(btn);
            });
        },

        commonSearchInList: function(){

            var route = [location.protocol, '//', location.host, location.pathname].join('');
            
            route += "?records="+$(".pin-common-records").val();
            if ($(".pin-common-search-text").val().trim() != '') {
                route += '&search=' + $(".pin-common-search-text").val().replace(/<[^>]+>/g, '').trim();
            }
            window.location.href = route;

        },

        clearCommonSearchInList: function(){
            var route = [location.protocol, '//', location.host, location.pathname].join('');
            route += "?records="+$(".pin-common-records").val();
            window.location.href = route;

        },

        complexSearchInList: function(form){
            var route = [location.protocol, '//', location.host, location.pathname].join('');
            route += "?records="+$(".pin-complex-records").val();
            form.find("input").each(function (index, field) {
                if ($(field).val().trim() != '' && $(field).val().trim() != null) {
                    route += "&"+$(field).attr('name')+"="+$(field).val().replace(/<[^>]+>/g, '').trim();
                }
            });
            form.find("select").each(function (index, field) {
                if ($(field).val() != '' && $(field).val() != null) {
                    route += "&"+$(field).attr('name')+"="+$(field).val();
                }
            });
            form.find("textarea").each(function (index, field) {
                if ($(field).val() != '' && $(field).val() != null) {
                    route += "&"+$(field).attr('name')+"="+$(field).val();
                }
            });
            window.location.href = route;
        },

        clearComplexSearchInList: function(){
            var route = [location.protocol, '//', location.host, location.pathname].join('');
            route += "?records="+$(".pin-complex-records").val();
            window.location.href = route;

        },

        updateInListAndRemoveRow: function(btn){
            AmouneeApp.disableButtonWithLoading(btn);
            $.post(btn.attr("data-action"))
            .done(function (data) {
                AmouneeApp.displayResultWithCallback(data, function () {
                    btn.closest(".pin-list-item").fadeOut(500).promise().done(function () {
                        if ($(document).find(".pin-list-item").length == 1) {
                            window.location.href = window.location.href;
                        }
                        btn.closest(".pin-list-item").remove();
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

    }
}();


// The following function is used to reduce writing same functions to update our forms in the system

jQuery(document).on('click', '.pin-common-search-in-list-btn', function (e){
    e.preventDefault();

    // The following function is actually the calling function which performs the common search, if you have an instance
    // where you need to make changes in your search
    // write a new search function in your JS file but never override this function unless a security issue is found

    TBHList.commonSearchInList();
});
// The following function is used to reduce writing same functions to reset forms in our system

jQuery(document).on('click', '.pin-common-search-in-list-refresh', function (e){
    e.preventDefault();

    // The following calling function will clear and reset the page, but keep the number of records as it is
    TBHList.clearCommonSearchInList();

});

// The following function is used to reduce writing for lists with advanced search in the system

jQuery(document).on('click', '.pin-complex-search-in-list-btn', function (e){
    e.preventDefault();

    
    var btn = $(".pin-complex-search-in-list-btn");
    var form = btn.closest('form');
    // The following function is actually the calling function which performs the complex search having multiple variable information, if you have an instance
    // where you need to make changes in your search
    // write a new search function in your JS file but never override this function unless a security issue is found

    TBHList.complexSearchInList(form);

});
// The following function is used to reduce writing same functions to reset lists with advanced search in the system

jQuery(document).on('click', '.pin-complex-search-in-list-refresh', function (e){
    e.preventDefault();

    // The following calling function will clear and reset the page, but keep the number of records as it is
    TBHList.clearComplexSearchInList();

});

// The following function updates the common list search whenever the number of records in the list are updated

jQuery('.pin-common-records').select2({
    minimumResultsForSearch: -1
}).change(function () {
    TBHList.commonSearchInList();
});

// The following function updates the complex list with advanced search whenever the number of records in the list are updated

jQuery('.pin-complex-records').select2({
    minimumResultsForSearch: -1
}).change(function () {
    
    var btn = $(".pin-complex-search-in-list-btn");
    var form = btn.closest('form');

    TBHList.complexSearchInList(form);
});

jQuery(document).on('click', '.pin-common-process-list-btn', function (e) {
    e.preventDefault();
    var btn = $(this);
    TBHList.updateInListAndRemoveRow(btn);
});