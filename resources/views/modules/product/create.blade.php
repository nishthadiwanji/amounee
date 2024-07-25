@extends('layouts.main')
@section('page_title')
@lang('heading.add_product')
@stop
@section('page_specific_body')
@include('modules.product._partials.product-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="form" id="ProductForm" name="ProductForm" data-action="{{route('product.store')}}" data-redirect-url="{{route('product.create')}}" onsubmit="return false;">
                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="far fa-plus-circle"></i>
                                </span>
                                <h3 class="card-label font-brand">
                                    @lang('heading.add_product')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" id="addProductBtn" class="btn btn-success">
                                    @lang('buttons.add')
                                </button>&nbsp;&nbsp;
                                <button type="button" id="addProductApproveBtn" class="btn btn-warning m-btn--air">
                                    @lang('buttons.addAndApprove')
                                </button>&nbsp;&nbsp;
                                <button type="reset" id="addProductResetBtn" class="btn btn-info m-btn--air pin-common-reset">
                                    @lang('buttons.reset')
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="is_approved" id="is_approved" value="0">
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="product_name">@lang('labels.product_name')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="product_name" class="form-control" placeholder="@lang('placeholders.product_name')" autofocus>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="sku">@lang('labels.sku')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="sku" class="form-control " placeholder="@lang('placeholders.sku')">
                                            </div>
                                        </div> -->
                                        <div class="col-md-3 col-12">
                                            <div class="form-group select-form-group ">
                                                <label class="form-control-label" for="artisan_id">@lang('labels.artisan_id')&nbsp;<span class="required">*</span></label>
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-category" id="artisan_id" name="artisan_id" style="width:100%;" data-placeholder="@lang('placeholders.artisan_id')">
                                                        <option></option>
                                                        @foreach($artisans as $artisan)
                                                        <option value="{{$artisan->id_token}}">
                                                            {{$artisan->first_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="material">@lang('labels.material')&nbsp;</label>
                                                <input type="text" name="material" class="form-control " placeholder="@lang('placeholders.material')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group select-form-group ">
                                                <label class="form-control-label" for="category_id">@lang('labels.category_id')&nbsp;<span class="required">*</span></label>
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-category" name="category_id" style="width:100%;" data-placeholder="@lang('placeholders.category_id')">
                                                        <option></option>
                                                        @foreach($categories as $category)
                                                        <option value="{{$category->id_token}}">
                                                            {{$category->category_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group select-form-group ">
                                                <label class="form-control-label" for="sub_category_id">@lang('labels.sub_category_id')&nbsp;<span class="required">*</span></label>
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-category" id="sub_category_id" name="sub_category_id" style="width:100%;" data-placeholder="@lang('placeholders.sub_category_id')">
                                                        <option></option>
                                                        @foreach($sub_categories as $sub_category)
                                                        <option value="{{$sub_category->id_token}}">
                                                            {{$sub_category->category_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="base_price">@lang('labels.base_price')&nbsp;<span class="required">*</span></label>
                                                <input type="text" id="base_price" name="base_price" class="form-control" placeholder="@lang('placeholders.base_price')">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="commission">@lang('labels.commission')&nbsp;</label>
                                                <input type="text" name="commission" id="commission" class="form-control" placeholder="@lang('placeholders.commission')">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="commission_unit">@lang('labels.commission_unit')&nbsp;</label>
                                            <div class="form-group select-form-group ">
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-category" name="commission_unit" id="commission_unit" style="width:100%;">
                                                        <option value="">select(price or %)</option>
                                                        <option value="rupee">&#8377;</option>
                                                        <option value="percentage">%</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="sales_price">@lang('labels.sales_price')</label>
                                                <input readonly type="number" id="sales_price" name="sales_price" class="form-control " placeholder="@lang('placeholders.sales_price')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group select-form-group ">
                                                <label class="form-control-label" for="stock_status">@lang('labels.stock_status')&nbsp;<span class="required">*</span></label>
                                                <div class="m-select2 m-select2--air">
                                                    <select id="stock_status" class="form-control m-select2 pin-category" name="stock_status" style="width:100%;" data-placeholder="@lang('placeholders.stock_status')">
                                                        @foreach($stock_status as $status)
                                                        <option value="{{$status}}">{{$status}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12" id="stock_div">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="stock">@lang('labels.opening_stock')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="stock" id="stock" class="form-control " placeholder="@lang('placeholders.stock')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="product_image">@lang('labels.product_image')&nbsp;<span class="required">*</span></label>
                                                <input type="file" name="product_image" id="product_image" class="form-control form-files" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                            <label class="form-control-label" for="product_gallery">@lang('labels.product_gallery')&nbsp;<span class="required">*</span></label>
                                            <input type="file" name="product_gallery[]" id="product_gallery" class="form-control" multiple required>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="tax_status">@lang('labels.tax_status')&nbsp;<span class="required">*</span></label>
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-category" id="tax_status" name="tax_status" style="width:100%;" data-placeholder="@lang('placeholders.tax_status')">
                                                @foreach($tax_status as $tax_status)
                                                    <option value="{{$tax_status}}">
                                                        {{$tax_status}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="tax_class">@lang('labels.tax_class')&nbsp;<span class="required">*</span></label>
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-category" id="tax_class" name="tax_class" style="width:100%;" data-placeholder="@lang('placeholders.tax_class')">
                                                @foreach($tax_class as $key => $value)
                                                <option value="{{$key}}">
                                                    {{$value}}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="zip">@lang('labels.hsn_code')&nbsp;<span class="required">*</span></label>
                                        <input type="text" name="hsn_code" class="form-control " placeholder="@lang('placeholders.hsn_code')">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label class="form-control-label" for="long_desc">@lang('labels.long_desc')&nbsp;<span class="required">*</span></label><br>
                                    <textarea class="form-control amounee-editor" id="long_desc" name="long_desc"></textarea>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-control-label" for="short_desc">@lang('labels.short_desc')&nbsp;<span class="required">*</span></label><br>
                                    <textarea class="form-control amounee-editor" id="short_desc" name="short_desc"></textarea>
                                </div>
                            </div>
                            <br>
                        </>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('page_specific_js')
@stop