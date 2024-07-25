@extends('layouts.main')
@section('page_title')
@lang('auth/frontend/heading.profile')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-2">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Settings</h5>
        </div>
    </div>
</div>
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="form" id="updateProfileForm" name="updateProfileForm" action="{{route('profile.store')}}" data-redirect-url="{{route('redirect')}}" onsubmit="return false;">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="la la-lock"></i>
                                </span>
                                <h3 class="card-label">
                                    @lang('auth/frontend/heading.profile')
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">                                
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="first_name">@lang('auth/frontend/labels.first_name')&nbsp;<span class="required">*</span></label>
                                        <input type="text" value="{{$userDetails->first_name}}" name="first_name" class="form-control " placeholder="@lang('auth/frontend/placeholders.first_name')">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="middle_name">@lang('auth/frontend/labels.middle_name')</label>
                                        <input type="text" value="{{$userDetails->middle_name}}" name="middle_name" class="form-control " placeholder="@lang('auth/frontend/placeholders.middle_name')">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="last_name">@lang('auth/frontend/labels.last_name')&nbsp;<span class="required">*</span></label>
                                        <input type="text" value="{{$userDetails->last_name}}" name="last_name" class="form-control " placeholder="@lang('auth/frontend/placeholders.last_name')">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="email">@lang('auth/frontend/labels.email')&nbsp;<span class="required">*</span></label>
                                        <input readonly type="text" value="{{$userDetails->email}}" name="email" class="form-control " placeholder="@lang('auth/frontend/placeholders.email')">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="country_code">@lang('auth/frontend/labels.country_code')&nbsp;<span class="required">*</span></label>
                                        <input type="text" value="{{$userDetails->country_code}}" name="country_code" class="form-control " placeholder="@lang('auth/frontend/placeholders.country_code')">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="phone_number">@lang('auth/frontend/labels.phone_number')&nbsp;<span class="required">*</span></label>
                                        <input type="text" value="{{$userDetails->phone_number}}" name="phone_number" class="form-control " placeholder="@lang('auth/frontend/placeholders.phone_number')">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions text-right">
                                <button type="button" class="btn btn-outline-success pin-submit pin-common-submit" tabindex="4">
                                    @lang('buttons.save')
                                </button>&nbsp;&nbsp;
                                <button type="reset" class="btn btn-outline-danger m-btn--air pin-common-reset" tabindex="5">
                                    @lang('buttons.cancel')
                                </button>
                            </div>
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
