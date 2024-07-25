@extends('layouts.main')
@section('page_title')
@lang('heading.add_artisan')
@stop
@section('page_specific_body')
@include('modules.artisan._partials.artisan-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="form" id="ArtisanForm" name="ArtisanForm" data-action="{{route('artisan.store')}}" data-redirect-url="{{route('artisan.create')}}" onsubmit="return false;">
                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="la la-user-plus"></i>
                                </span>
                                <h3 class="card-label font-brand">
                                    @lang('heading.add_artisan')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" id="addArtisanBtn" class="btn btn-success">
                                    @lang('buttons.register')
                                </button>&nbsp;&nbsp;
                                <input type="hidden" name="is_approved" id="is_approved" value="">
                                <button type="button" id="addArtisanApproveBtn" class="btn btn-warning m-btn--air">
                                    @lang('buttons.registerandapprove')
                                </button>&nbsp;&nbsp;
                                <button type="reset" id="addArtisanResetBtn" class="btn btn-info m-btn--air pin-common-reset">
                                    @lang('buttons.reset')
                                </button>
                            </div>
                        </div>
                        <!-- <input type="hidden" name="is_approved" id="is_approved" value="0"> -->
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="vendor_picture">@lang('labels.vendor_picture')</label>
                                        <input type="file" name="vendor_picture" id="vendor_picture" class="form-files">
                                    </div>
                                </div>
                                <div class="col-md-9 col-12">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="first_name">@lang('labels.first_name')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="first_name" class="form-control" placeholder="@lang('placeholders.first_name')" autofocus>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="last_name">@lang('labels.last_name')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="last_name" class="form-control " placeholder="@lang('placeholders.last_name')">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="trade_name">@lang('labels.trade_name')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="trade_name" class="form-control " placeholder="@lang('placeholders.trade_name')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="gst">@lang('labels.gst')&nbsp;</label>
                                                <input type="text" name="gst" class="form-control " placeholder="@lang('placeholders.gst')">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group select-form-group ">
                                                <label class="form-control-label" for="category_id">@lang('labels.craft_category')&nbsp;<span class="required">*</span></label>
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-category" name="category_id" style="width:100%;" data-placeholder="Craft Practiced">
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
                                        <div class="col-md-4 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="commission">@lang('labels.commission')</label>
                                                <input type="text" name="commission" class="form-control " placeholder="@lang('placeholders.commission')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-2 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="country_code">@lang('labels.country_code')<span class="required">*</span></label>
                                                <input type="text" name="country_code" class="form-control " placeholder="@lang('placeholders.country_code')">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="phone_number">@lang('labels.phone_number')<span class="required">*</span></label>
                                                <input type="text" name="phone_number" class="form-control " placeholder="@lang('placeholders.phone_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="email">@lang('labels.email_address')&nbsp;<span class="required">*</span></label>
                                                <input type="email" name="email" class="form-control " placeholder="@lang('placeholders.email_address')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                            <br>
                            <h6>Address: <span class="required">*</span></h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="street1">@lang('labels.street1')&nbsp;</label>
                                        <input type="text" name="street1" class="form-control " placeholder="@lang('placeholders.street1')">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="street2">@lang('labels.street2')&nbsp;</label>
                                        <input type="text" name="street2" class="form-control " placeholder="@lang('placeholders.street2')">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="zip">@lang('labels.zip')&nbsp;</label>
                                        <input type="text" name="zip" class="form-control " placeholder="@lang('placeholders.zip')">
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="city">@lang('labels.city')&nbsp;</label>
                                        <input type="text" name="city" class="form-control " placeholder="@lang('placeholders.city')">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="state">@lang('labels.state')&nbsp;</label>
                                        <input type="text" name="state" class="form-control " placeholder="@lang('placeholders.state')">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="state">@lang('labels.country')&nbsp;</label>
                                        <input type="text" name="country" class="form-control " placeholder="@lang('placeholders.country')">
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="id_proof">@lang('labels.id_proof')</label>
                                        <input type="file" name="id_proof[]" id="id_proof" class="form-control" required multiple>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="artisan_cards">@lang('labels.artisan_cards')</label>
                                        <input type="file" name="artisan_cards" id="artisan_cards" class="form-control form-files">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <input type="checkbox" id="has_awards" />&nbsp;<label for="has_awards">@lang('labels.awards')</label>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div id="block" class="col-md-6 col-12" style="display:none;">
                                    <textarea name="awards" class="form-control" id="awards" placeholder="@lang('placeholders.awards')" rows="10" style="resize:none;"></textarea>
                                </div>
                            </div>
                            <br>

                            <h6>Payment Option: <span class="required">*</span></h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="account_name">@lang('labels.account_name')&nbsp;</label>
                                        <input type="text" name="account_name" class="form-control " placeholder="@lang('placeholders.account_name')">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="account_number">@lang('labels.account_number')&nbsp;</label>
                                        <input type="text" name="account_number" class="form-control " placeholder="@lang('placeholders.account_number')">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="bank_name">@lang('labels.bank_name')&nbsp;</label>
                                        <input type="text" name="bank_name" class="form-control " placeholder="@lang('placeholders.bank_name')">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="ifsc">@lang('labels.ifsc')&nbsp;</label>
                                        <input type="text" name="ifsc" class="form-control " placeholder="@lang('placeholders.ifsc')">
                                    </div>
                                </div>
                                
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="passbook_picture">@lang('labels.passbook_picture')</label>
                                        <input type="file" name="passbook_picture" id="passbook_picture" class="form-files" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop