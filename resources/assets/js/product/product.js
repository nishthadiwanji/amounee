var Product = function () {
    var handleManageStock = function () {
        $("#manageStockForm").validate({
            rules: {
                stock_status: {
                    required: true
                },
                stock: {
                    digits: true
                }
            },
            messages: {
                stock_status: {
                    required: "Stock status is required."
                },
                stock: {
                    digits: "Only digits allowed."
                }
            }
        });

        $('#stock_status').change(function(){
            var stock_status=$("#stock_status").val();
            if(stock_status=='In stock')
            {
                $('#stock_val').show();
            }
            else
            {
                $('#stock').val("");
                $('#stock_val').hide();
            }
        });

        $('button.manage-stock').click(function(){
            e.preventDefault();
             var form = $('#manageStockForm');
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
    var initProductCreateForm = function () {
        $('.amounee-editor').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen' ] ]
            ]
        });
        $("#product_image").fileinput({
            showUpload: false,
            showRemove: true,
            showPreview: false,
            minFileCount: 1,
            maxFileCount: 1,
            allowedFileExtensions: ['jpeg', 'jpg', 'png'],
            msgErrorClass: 'file-error-message d-none',
            minFileSize: 1,
            maxFileSize: 2000,
            msgInvalidFileExtension: 'Please select JPG, JPEG or PNG file.',
            msgPlaceholder: 'Select Product Image',
            browseLabel: 'Browse',
            browseClass: 'btn btn-sm btn-info',
            removeClass: 'btn btn-sm btn-danger',
            initialPreview: [
                            "no image"
            ],
            initialCaption: "Select Product Image",
        });
         $("#product_image").change(function(){
            $(this).valid();
        });
        $("#product_gallery").fileinput({
            showUpload: false,
            showRemove: true,
            showPreview: false,
            minFileCount: 1,
            maxFileCount: 7,
            allowedFileExtensions: ['jpeg', 'jpg', 'png'],
            msgErrorClass: 'file-error-message d-none',
            minFileSize: 1,
            maxFileSize: 2000,
            msgInvalidFileExtension: 'Invalid File Format',
            msgPlaceholder: 'Select images',
            browseLabel: 'Browse',
            browseClass: 'btn btn-sm btn-info',
            removeClass: 'btn btn-sm btn-danger',
            initialPreview: [
                "no image"
            ],
            initialCaption: "Select Images",
        });
        $("#product_gallery").change(function(){
            $(this).valid();
        });
        $("#base_price").change(function(){
            var base_price=$('#base_price').val();
            var commission=$('#commission').val();
            var commission_unit=$('#commission_unit').val();
            if(commission)//prouduct level commission exist
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
                
            }
            else //check for sub category level , artisan level and global level commission
            {
                //code for fetching subcategory level commission
                var sub_category_commission;
                var sales_price=parseInt(base_price);
                var id=$("#sub_category_id").val();
                if(id)
                {
                    $.ajax({
                        url:'http://amounee.test/category/'+id+'/details',
                        method:'get',
                        async:false,
                        success:function (res) {
                            sub_category_commission=parseInt(res.commission);
                            sub_category_commission=(sub_category_commission*base_price)/100;
                            if(sub_category_commission)
                            {
                                sales_price+=sub_category_commission;
                            }
                            $('#sales_price').val(sales_price);
                        }
                    });
                }
                if(!sub_category_commission)
                {
                     //check for artisan level commission
                     var artisan_commission;
                        var artisan_id=$('#artisan_id').val();
                        if(artisan_id)
                        {
                            $.ajax({
                                url:'http://amounee.test/artisan/'+artisan_id+'/details',
                                method:'get',
                                async:false,
                                success:function (res) {
                                    artisan_commission=parseInt(res.commission);
                                    artisan_commission=(artisan_commission*base_price)/100;
                                    if(artisan_commission)
                                    {
                                        sales_price+=artisan_commission;
                                    }
                                    $('#sales_price').val(sales_price);
                                }
                            });
                        }
                }
                //global level commission
                if(base_price==sales_price)
                {   
                    var sales_price;
                    commission=(25*base_price)/100;
                    sales_price+=commission;
                    $('#sales_price').val(sales_price);
                }
            }
        });
        $("#commission").change(function(){
            var base_price=$('#base_price').val();
            if(!base_price)
            {
                base_price=0;
            }
            var commission=$('#commission').val();
            var commission_unit=$('#commission_unit').val();
            if(commission)
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
            }
            else
            {
                var sales_price=parseInt(base_price);
            }
            $('#sales_price').val(sales_price);
        });
        $("#commission_unit").change(function(){
            var base_price=$('#base_price').val();
            var commission=$('#commission').val();
            var commission_unit=$(this).val();
            if(commission)
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
            }
            else
            {
                var sales_price=parseInt(base_price);
            }
            $('#sales_price').val(sales_price);
        });
        $("#artisan_id").change(function(){
            var base_price=$('#base_price').val();
            var commission=$('#commission').val();
            var commission_unit=$('#commission_unit').val();
            if(commission)//prouduct level commission exist
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
            }
            else //check for sub category level , artisan level and global level commission
            {
                //code for fetching subcategory level commission
                var sub_category_commission;
                var sales_price=parseInt(base_price);
                var id=$("#sub_category_id").val();
                if(id)
                {
                    $.ajax({
                        url:'http://amounee.test/category/'+id+'/details',
                        method:'get',
                        async:false,
                        success:function (res) {
                            sub_category_commission=parseInt(res.commission);
                            sub_category_commission=(sub_category_commission*base_price)/100;
                            if(sub_category_commission)
                            {
                                sales_price+=sub_category_commission;
                            }
                            $('#sales_price').val(sales_price);
                        }
                    });
                }
                if(!sub_category_commission)
                {
                     //check for artisan level commission
                     var artisan_commission;
                        var artisan_id=$('#artisan_id').val();
                        if(artisan_id)
                        {
                            $.ajax({
                                url:'http://amounee.test/artisan/'+artisan_id+'/details',
                                method:'get',
                                async:false,
                                success:function (res) {
                                    artisan_commission=parseInt(res.commission);
                                    artisan_commission=(artisan_commission*base_price)/100;
                                    if(artisan_commission)
                                    {
                                        sales_price+=artisan_commission;
                                    }
                                    $('#sales_price').val(sales_price);
                                }
                            });
                        }
                }
                 //global level commission
                 if(base_price==sales_price)
                 {   
                     var sales_price;
                     commission=(25*base_price)/100;
                     sales_price+=commission;
                     $('#sales_price').val(sales_price);
                 }
            }
        });
        $("#sub_category_id").change(function(){
            var base_price=$('#base_price').val();
            var commission=$('#commission').val();
            var commission_unit=$('#commission_unit').val();
            if(commission)//prouduct level commission exist
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
            }
            else //check for sub category level , artisan level and global level commission
            {
                //code for fetching subcategory level commission
                var artisan_commission;
                var sub_category_commission;
                var sales_price=parseInt(base_price);
                var id=$("#sub_category_id").val();
                if(id)
                {
                    $.ajax({
                        url:'http://amounee.test/category/'+id+'/details',
                        method:'get',
                        async:false,
                        success:function (res) {
                            sub_category_commission=parseInt(res.commission);
                            sub_category_commission=(sub_category_commission*base_price)/100;
                            if(sub_category_commission)
                            {
                                sales_price+=sub_category_commission;
                            }
                            $('#sales_price').val(sales_price);
                        }
                    });
                }
                if(!sub_category_commission)
                {
                     //check for artisan level commission
                     var sales_price=parseInt(base_price);
                        var artisan_id=$('#artisan_id').val();
                        if(artisan_id)
                        {
                            $.ajax({
                                url:'http://amounee.test/artisan/'+artisan_id+'/details',
                                method:'get',
                                async:false,
                                success:function (res) {
                                    artisan_commission=parseInt(res.commission);
                                    artisan_commission=(artisan_commission*base_price)/100;
                                    if(artisan_commission)
                                    {
                                        sales_price+=artisan_commission;
                                    }
                                    $('#sales_price').val(sales_price);
                                }
                            });
                        }
                }
                //global level commission
                if(base_price==sales_price)
                {   
                    var sales_price;
                    commission=(25*base_price)/100;
                    sales_price+=commission;
                    $('#sales_price').val(sales_price);
                }
            }
        });
        $('#stock_status').change(function(){
            var stock_status=$("#stock_status").val();
            if(stock_status=='In stock')
            {
                $('#stock_div').show();
            }
            else
            {
                $('#stock').val("");
                $('#stock_div').hide();
            }
        });
    }

    var handleProductStore = function () 
    {     
        $("#ProductForm").validate({
           
            rules: {
                                product_name: {
                                    required: true,
                                    checkForWhiteSpace: true
                                },
                                sku: {
                                    // required: true,
                                    checkForWhiteSpace: true
                                },
                                artisan_id: {
                                    required: true
                                },
                                stock_status: {
                                    required:true,
                                    checkForWhiteSpace: true
                                },
                                stock:{
                                    required: function(element){
                                            return $("#stock_status").val()=="In stock";
                                        },
                                        digits:true,
                                        checkForWhiteSpace: true
                                      
                                },
                                category_id:{
                                    required:true
                                },
                                sub_category_id: {
                                    required:true
                                },
                                base_price:{
                                    digits: true,
                                    required:true,
                                    checkForWhiteSpace: true
                                },
                                product_image: {
                                    filesize: 2048,
                                    accept: "image/jpg,jpeg,png",
                                    extension: "jpg|jpeg|png",
                                    filesize: 2048,
                                },
                                hsn_code:{
                                    required:true,
                                    checkForWhiteSpace: true,
                                    greater_than_zero:true
                                },
                                commission:{
                                    checkForWhiteSpace: true,
                                    validNumber:true
                                },
                                commission_unit:{
                                    required: function(element){
                                        return $("#commission").val().trim()!="";
                                    }
                                },
                                material:{
                                    checkForWhiteSpace: true
                                },
                                sales_price:{
                                    checkForWhiteSpace: true
                                },
                                'product_gallery[]': {
                                    filesize: 2048,
                                    extension: "jpg|jpeg|png"
                                },
                
                            },
                            messages: {
                                product_name: {
                                    required: "Product name is required."
                                },
                                sku: {
                                    // required: "sku is required.",
                                    checkForWhiteSpace: "Spaces are not allowed."
                                },
                                artisan_id: {
                                    required: "Artisan name is required.",
                                },
                                stock_status: {
                                    required: "stock status is required.",
                                },
                                category_id: {
                                    required: "category is required.",
                                },
                                sub_category_id: {
                                    required: "sub category is required.",
                                },
                                base_price:{
                                    required: "base price is required.",
                                },
                                hsn_code:{
                                    required: "hsn code is required.",
                                },
                                sales_price:{
                                    required: "sales price is required.",
                                },
                                'product_gallery[]':{
                                    required:"Product Gallery Images are required."
                                },
                                product_image:{
                                    required:"Product Image is required"
                                }
                            }
        });

        $('#addProductBtn').click(function(e){
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
            var totalfiles = document.getElementById('product_gallery').files.length;
            for (var index = 0; index < totalfiles; index++) {
                var item = document.getElementById('product_gallery');
                var extension = item.files[0].name.split('.');
                var temp2 = extension[extension.length - 1].toLowerCase();
                var size = parseFloat(item.files[0].size / 1024).toFixed(2);
                if (size > 2048) {
                    toastr.warning("Maximum upload file size is 2MB", "Size Alert");
                    return false;
                } else {
                    formData.append(item.name, document.getElementById('product_gallery').files[index]);
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
        $('#addProductApproveBtn').click(function(e){
            $('#is_approved').val('1');
            $('#addProductBtn').trigger('click');
        });
        $("#addProductResetBtn").click(function(e){
            $("#tax_status").val('Taxable');
            $("#tax_status").trigger('change');
        });
    }

    var handleManageStockModal = function(){

        // $(document).on('click', '.manage_stock_btn', function (e) {

        //     $("#manageStockForm").attr("data-action",$(this).attr("data-url"));
        //     $("#stockModal").modal('show');
        // });

        $(".manage_stock_btn").click(function(){
            var btn = $(this);
            var product_stock_url = btn.attr("data-action");
            var redirect_url = btn.attr("data-redirect-url");
            $("#manageStockForm").attr("action",product_stock_url);
            $("#manageStockForm").attr("data-redirect-url",redirect_url);
        });

        $('#stockModal').on('hidden.bs.modal', function(){
            AmouneeApp.resetForm($('#manageStockForm'));

        });
    }

    var initProductEditForm = function () {
        $("#base_price").change(function(){
            var base_price=$('#base_price').val();
            var commission=$('#commission').val();
            var commission_unit=$('#commission_unit').val();
            if(commission)//prouduct level commission exist
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
                
            }
            else //check for sub category level , artisan level and global level commission
            {
                //code for fetching subcategory level commission
                var sub_category_commission;
                var sales_price=parseInt(base_price);
                var id=$("#sub_category_id").val();
                if(id)
                {
                    $.ajax({
                        url:'http://amounee.test/category/'+id+'/details',
                        method:'get',
                        async:false,
                        success:function (res) {
                            sub_category_commission=parseInt(res.commission);
                            sub_category_commission=(sub_category_commission*base_price)/100;
                            if(sub_category_commission)
                            {
                                sales_price+=sub_category_commission;
                            }
                            $('#sales_price').val(sales_price);
                        }
                    });
                }
                if(!sub_category_commission)
                {
                     //check for artisan level commission
                     var artisan_commission;
                        var artisan_id=$('#artisan_id').val();
                        if(artisan_id)
                        {
                            $.ajax({
                                url:'http://amounee.test/artisan/'+artisan_id+'/details',
                                method:'get',
                                async:false,
                                success:function (res) {
                                    artisan_commission=parseInt(res.commission);
                                    artisan_commission=(artisan_commission*base_price)/100;
                                    if(artisan_commission)
                                    {
                                        sales_price+=artisan_commission;
                                    }
                                    $('#sales_price').val(sales_price);
                                }
                            });
                        }
                }
                //global level commission
                if(base_price==sales_price)
                {   
                    var sales_price;
                    commission=(25*base_price)/100;
                    sales_price+=commission;
                    $('#sales_price').val(sales_price);
                }
            }
        });
        $("#commission").change(function(){
            var base_price=$('#base_price').val();
            if(!base_price)
            {
                base_price=0;
            }
            var commission=$('#commission').val();
            var commission_unit=$('#commission_unit').val();
            if(commission)
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
            }
            else
            {
                var sales_price=parseInt(base_price);
            }
            $('#sales_price').val(sales_price);
        });
        $("#commission_unit").change(function(){
            var base_price=$('#base_price').val();
            var commission=$('#commission').val();
            var commission_unit=$(this).val();
            if(commission)
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
            }
            else
            {
                var sales_price=parseInt(base_price);
            }
            $('#sales_price').val(sales_price);
        });
        $("#artisan_id").change(function(){
            var base_price=$('#base_price').val();
            var commission=$('#commission').val();
            var commission_unit=$('#commission_unit').val();
            if(commission)//prouduct level commission exist
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
            }
            else //check for sub category level , artisan level and global level commission
            {
                //code for fetching subcategory level commission
                var sub_category_commission;
                var sales_price=parseInt(base_price);
                var id=$("#sub_category_id").val();
                if(id)
                {
                    $.ajax({
                        url:'http://amounee.test/category/'+id+'/details',
                        method:'get',
                        async:false,
                        success:function (res) {
                            sub_category_commission=parseInt(res.commission);
                            sub_category_commission=(sub_category_commission*base_price)/100;
                            if(sub_category_commission)
                            {
                                sales_price+=sub_category_commission;
                            }
                            $('#sales_price').val(sales_price);
                        }
                    });
                }
                if(!sub_category_commission)
                {
                     //check for artisan level commission
                     var artisan_commission;
                        var artisan_id=$('#artisan_id').val();
                        if(artisan_id)
                        {
                            $.ajax({
                                url:'http://amounee.test/artisan/'+artisan_id+'/details',
                                method:'get',
                                async:false,
                                success:function (res) {
                                    artisan_commission=parseInt(res.commission);
                                    artisan_commission=(artisan_commission*base_price)/100;
                                    if(artisan_commission)
                                    {
                                        sales_price+=artisan_commission;
                                    }
                                    $('#sales_price').val(sales_price);
                                }
                            });
                        }
                }
                 //global level commission
                 if(base_price==sales_price)
                 {   
                     var sales_price;
                     commission=(25*base_price)/100;
                     sales_price+=commission;
                     $('#sales_price').val(sales_price);
                 }
            }
        });
        $("#sub_category_id").change(function(){
            var base_price=$('#base_price').val();
            var commission=$('#commission').val();
            var commission_unit=$('#commission_unit').val();
            if(commission)//prouduct level commission exist
            {
                commission=parseInt(commission);
                base_price=parseInt(base_price);
                if(commission_unit=='rupee')
                {
                    var sales_price=base_price+commission;
                    $('#sales_price').val(sales_price);
                }
                else if(commission_unit=='percentage')
                {
                    var tmp=((commission*base_price)/100);
                    var sales_price=base_price+tmp;
                    $('#sales_price').val(sales_price);
                }
                else
                {
                    $('#sales_price').val('');
                }
            }
            else //check for sub category level , artisan level and global level commission
            {
                //code for fetching subcategory level commission
                var artisan_commission;
                var sub_category_commission;
                var sales_price=parseInt(base_price);
                var id=$("#sub_category_id").val();
                if(id)
                {
                    $.ajax({
                        url:'http://amounee.test/category/'+id+'/details',
                        method:'get',
                        async:false,
                        success:function (res) {
                            sub_category_commission=parseInt(res.commission);
                            sub_category_commission=(sub_category_commission*base_price)/100;
                            if(sub_category_commission)
                            {
                                sales_price+=sub_category_commission;
                            }
                            $('#sales_price').val(sales_price);
                        }
                    });
                }
                if(!sub_category_commission)
                {
                     //check for artisan level commission
                     var sales_price=parseInt(base_price);
                        var artisan_id=$('#artisan_id').val();
                        if(artisan_id)
                        {
                            $.ajax({
                                url:'http://amounee.test/artisan/'+artisan_id+'/details',
                                method:'get',
                                async:false,
                                success:function (res) {
                                    artisan_commission=parseInt(res.commission);
                                    artisan_commission=(artisan_commission*base_price)/100;
                                    if(artisan_commission)
                                    {
                                        sales_price+=artisan_commission;
                                    }
                                    $('#sales_price').val(sales_price);
                                }
                            });
                        }
                }
                //global level commission
                if(base_price==sales_price)
                {   
                    var sales_price;
                    commission=(25*base_price)/100;
                    sales_price+=commission;
                    $('#sales_price').val(sales_price);
                }
            }
        });
        $('#stock_status').change(function(){
            var stock_status=$("#stock_status").val();
            if(stock_status=='In stock')
            {
                $('#stock_div').show();
            }
            else
            {
                $('#stock').val("");
                $('#stock_div').hide();
            }
        });
        var preview = [
            "no image"
        ];
        var previewCaption = "Select Product Image";
        var broweseLabel = "Browse";
        var showRemove = true;
        if ($("#edit_product_image").is("[data-product-image-url]")) {
            preview = [
                "<img src='" + $("#edit_product_image").attr("data-product-image-url") + "' height='180px' width='180px'>"
            ];
            previewCaption = "1 Product Picture uploaded";
            broweseLabel = "Change";
            showRemove = false;
        }
        $("#edit_product_image").fileinput({
            showUpload: false,
            showRemove: showRemove,
            previewFileType: 'image',
            minFileCount: 1,
            maxFileCount: 1,
            allowedFileExtensions: ['jpeg', 'jpg', 'png'],
            minFileSize: 1,
            //maxFileSize: 2000,
            msgInvalidFileExtension: 'Please select JPG, JPEG or PNG file.',
            browseLabel: broweseLabel,
            browseClass: 'btn btn-sm btn-info m-btn--air',
            removeClass: 'btn btn-sm btn-danger m-btn--air',
            initialPreview: preview,
            initialCaption: previewCaption
        });
        $("#edit_product_image").trigger('fileclear');
    }

    var handleproductUpdate = function() {
        $('#updateProductBtn').click(function(e){
        
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
            var totalfiles = document.getElementById('product_gallery').files.length;
            for (var index = 0; index < totalfiles; index++) {
                var item = document.getElementById('product_gallery');
                var extension = item.files[0].name.split('.');
                var temp2 = extension[extension.length - 1].toLowerCase();
                var size = parseFloat(item.files[0].size / 1024).toFixed(2);
                if (size > 2048) {
                    toastr.warning("Maximum upload file size is 2MB", "Size Alert");
                    return false;
                } else {
                    formData.append(item.name, document.getElementById('product_gallery').files[index]);
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

    return {
        init: function () {
            handleManageStock();
            handleManageStockModal();
            initProductCreateForm();
            handleProductStore();
            initProductEditForm();
            handleproductUpdate();
        }
    };
}();
jQuery(document).ready(function () {
    Product.init();
});