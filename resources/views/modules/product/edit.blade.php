@extends('layouts.main')
@section('page_title')
@lang('heading.edit_product')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.product._partials.product-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="m-form m-form--fit" id="ProductForm" name="ProductForm" data-action="{{route('product.update',[$product->id_token])}}" data-redirect-url="{{route('product.index')}}" onsubmit="return false;">
                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="fas fa-pencil"></i>
                                </span>
                                <h3 class="card-label font-brand">
                                    @lang('heading.edit_product')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <input type="hidden" value="PUT" name="_method">
                                <button type="button" class="btn btn-success pin-submit" id="updateProductBtn">@lang('buttons.update')</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group m-form__group has-feedback">
                                        <label class="form-control-label" for="product_image">@lang('labels.product_image')<span class="required">*</span></label>
                                        @if(!is_null($product_image))
                                        <input type="file" name="product_image" id="edit_product_image" style="max-height:100%; max-width:100%;" class="form-files" data-product-image-url="{{ $product_image->display_url}}" tabindex="1">
                                        @else
                                        <input type="file" name="product_image" id="edit_product_image" style="max-height:100%; max-width:100%;" class="form-files" tabindex="1">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-9 col-12">
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group m-form__group">
                                                <label class="form-control-label" for="product_name">@lang('labels.product_name')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="product_name" class="form-control " value="{{$product->product_name}}" placeholder="@lang('placeholders.product_name')">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group m-form__group">
                                                <label class="form-control-label" for="sku">@lang('labels.sku')&nbsp;<span class="required">*</span></label>
                                                <input readonly type="text" name="sku" class="form-control " value="{{$product->sku}}" placeholder="@lang('placeholders.sku')">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group select-form-group ">
                                                <label class="form-control-label" for="artisan_id">@lang('labels.artisan_id')&nbsp;<span class="required">*</span></label>
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-category" name="artisan_id" id="artisan_id" style="width:100%;" data-placeholder="@lang('placeholders.artisan_id')">
                                                        <option></option>
                                                        @foreach($artisans as $artisan)
                                                        <option value="{{$artisan->id_token}}" {{ $product->artisan_id == $artisan->id ? 'selected' : ''}}>
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
                                                <input type="text" name="material" class="form-control " value="{{$product->material}}" placeholder="@lang('placeholders.state')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group select-form-group ">
                                                    <label class="form-control-label" for="category_id">@lang('labels.category_id')&nbsp;<span class="required">*</span></label>
                                                    <div class="m-select2 m-select2--air">
                                                        <select class="form-control m-select2 pin-category" name="category_id" style="width:100%;" data-placeholder="@lang('placeholders.category_id')">
                                                            <option></option>
                                                            @foreach($categories as $category)
                                                            <option value="{{$category->id_token}}" {{$product->category_id == $category->id ? 'selected' : ''}}>
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
                                                    <select class="form-control m-select2 pin-category" name="sub_category_id" id="sub_category_id" style="width:100%;" data-placeholder="@lang('placeholders.sub_category_id')">
                                                        <option></option>
                                                        @foreach($sub_categories as $sub_category)
                                                        <option value="{{$sub_category->id_token}}" {{$product->sub_category_id == $sub_category->id ? 'selected' : ''}}>
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
                                            <div class="form-group m-form__group">
                                                <label class="form-control-label" for="base_price">@lang('labels.base_price')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="base_price" id="base_price" class="form-control " value="{{$product->base_price}}" placeholder="@lang('placeholders.base_price')">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="city">@lang('labels.commission')&nbsp;</label>
                                                <input type="text" name="commission" id="commission" class="form-control " value="{{$product->product_comm_number}}" placeholder="@lang('placeholders.commission')">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="commission_unit">@lang('labels.commission_unit')&nbsp;</label>
                                                <div class="form-group select-form-group ">
                                                    <div class="m-select2 m-select2--air">
                                                        <select class="form-control m-select2 pin-category" name="commission_unit" id="commission_unit" style="width:100%;">
                                                            <option value="">select(price or %)</option>
                                                            <option value="rupee"  {{$product->product_comm_type == 'rupee' ? 'selected' : ''}}>&#8377;</option>
                                                            <option value="percentage" {{$product->product_comm_type == 'percentage' ? 'selected' : ''}}>%</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="country">@lang('labels.sales_price')</label>
                                                <input readonly type="text" name="sales_price" id="sales_price" class="form-control " value="{{$product->selling_price}}" placeholder="@lang('placeholders.sales_price')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
        
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group select-form-group ">
                                                <label class="form-control-label" for="stock_status">@lang('labels.stock_status')&nbsp;<span class="required">*</span></label>
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-category" id="stock_status" name="stock_status" style="width:100%;" data-placeholder="@lang('placeholders.stock_status')">
                                                        <option></option>
                                                        @foreach($stock_status as $status)
                                                        <option value="{{$status}}" {{$product->stock_status == $status ? 'selected' : ''}}>{{$status}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12" id="stock_div">
                                            <div class="form-group m-form__group">
                                                <label class="form-control-label" for="opening_stock">@lang('labels.opening_stock')</label>
                                                <input value="{{ $product->stock}}" type="text" name="stock" id="stock" class="form-control " placeholder="@lang('placeholders.stock')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="tax_status">@lang('labels.tax_status')</label>
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-category" name="tax_status" style="width:100%;" data-placeholder="@lang('placeholders.tax_status')">
                                                <option></option>
                                                @foreach($tax_status as $tax_status)
                                                    <option value="{{$tax_status}}" {{$product->tax_status == $tax_status ? 'selected' : ''}}>
                                                        {{$tax_status}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="tax_class">@lang('labels.tax_class')</label>
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-category" name="tax_class" style="width:100%;" data-placeholder="@lang('placeholders.tax_class')">
                                                <option></option>
                                                {{-- @foreach($tax_class as $tax_class)
                                                    <option value="{{$tax_class}}" {{$product->tax_class == $tax_class ? 'selected' : ''}}>
                                                        {{$tax_class}}
                                                    </option>
                                                @endforeach --}}
                                                @foreach($tax_class as $key => $value)
                                                <option value="{{$value}}" {{$product->tax_class == $value ? 'selected' : ''}}>
                                                    {{$key}}
                                                </option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="hsn_code">@lang('labels.hsn_code')&nbsp;<span class="required">*</span></label>
                                        <input type="text" name="hsn_code" class="form-control " value="{{$product->hsn_code}}" placeholder="@lang('placeholders.hsn_code')">
                                    </div>
                                </div>
                            </div>
                            <br>
                             <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group m-form__group has-feedback">
                                        <label class="form-control-label" for="product_gallery[]">@lang('labels.product_gallery')</label>
                                        <input type="file" name="product_gallery[]" id="product_gallery" tabindex="3" multiple>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label class="form-control-label" for="long_desc">@lang('labels.long_desc')&nbsp;<span class="required">*</span></label><br>
                                    <textarea class="amounee-editor" id="long_desc" name="long_desc">{{ $product->long_desc }}</textarea>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-control-label" for="short_desc">@lang('labels.short_desc')&nbsp;<span class="required">*</span></label><br>
                                    <textarea class="amounee-editor" id="short_desc" name="short_desc">{{ $product->short_desc }}</textarea>
                                </div>
                            </div>
                            <br>
                            <br>
                            <h6>Proudct Gallery Images:</h6>
                            @include('modules.product._partials.product-gallery')
                            <br>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('page_specific_js')
@stop