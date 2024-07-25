@extends('layouts.main')
@section('page_title')
@lang('auth/frontend/heading.change_password')
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
            <div class="col-md-6 col-sm-12 col-xs-12">
                <form class="form" id="changePasswordForm" name="changePasswordForm" action="{{route('password.attempt-change')}}" data-redirect-url="{{route('redirect')}}" onsubmit="return false;">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="la la-lock"></i>
                                </span>
                                <h3 class="card-label">
                                    @lang('auth/frontend/heading.change_password')
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-control-label" for="password">@lang('auth/frontend/labels.current_password')</label>
                                <span class="required">*</span>
                                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="@lang('auth/frontend/placeholders.current_password')" tabindex="1" autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="password">@lang('auth/frontend/labels.new_password')</label>
                                <span class="required">*</span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="@lang('auth/frontend/placeholders.new_password')" tabindex="2">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="password_confirmation">@lang('auth/frontend/labels.confirm_new_password')</label>
                                <span class="required">*</span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="@lang('auth/frontend/placeholders.confirm_new_password')" tabindex="3">
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
