@extends('layouts.main')
@section('page_title')
@lang('heading.profile')
@stop
@section('page_specific_css')
@stop @section('page_specific_body')
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <form class="m-form m-form--fit" id="editProfileForm" name="editProfileForm" data-action="{{route('profile.update')}}" data-redirect-url="{{route('home')}}" onsubmit="return false;">
               <input type="hidden" value="PUT" name="_method" />
                <div class="card-header">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <span class="m-portlet__head-icon">
                                <i class="la la-user"></i>
                            </span>
                            <h3 class="m-portlet__head-text m--font-brand">
                                @lang('heading.edit_profile')
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding: 1rem 1rem;">
					<div class="m-form__section" style="margin: 20px 0 20px 0;">
						<div class="row">
                            <div class="col-md-8 col-12">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group m-form__group">
                                            <label class="form-control-label" for="first_name">@lang('labels.first_name')&nbsp;<span class="required">*</span></label>
                                            <input type="text" name="first_name" id="first_name" class="form-control " value="{{$member->user->first_name}}" placeholder="@lang('placeholders.first_name')" tabindex="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group m-form__group">
                                            <label class="form-control-label" for="middle_name">@lang('labels.middle_name')</label>
                                            <input type="text" name="middle_name" id="middle_name" class="form-control " value="{{$member->user->middle_name}}" placeholder="@lang('placeholders.middle_name')" tabindex="2">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group m-form__group">
                                            <label class="form-control-label" for="last_name">@lang('labels.last_name')&nbsp;<span class="required">*</span></label>
                                            <input type="text" name="last_name" id="last_name" class="form-control " value="{{$member->user->last_name}}" placeholder="@lang('placeholders.last_name')" tabindex="3">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group m-form__group">
                                            <label class="form-control-label" for="employee_id">@lang('labels.employee_id')&nbsp;<span class="required">*</span></label>
                                            <input type="text" name="employee_id" id="employee_id" class="form-control " value="{{$member->employee_id}}" placeholder="@lang('placeholders.employee_id')" tabindex="4">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group m-form__group">
                                            <label class="form-control-label" for="email">@lang('labels.email_address')&nbsp;<span class="required">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control " value="{{$member->user->email}}" placeholder="@lang('placeholders.email_address')" tabindex="5">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group m-form__group">
                                            <label class="form-control-label" for="phone_number">@lang('labels.phone_number')&nbsp;<span class="required">*</span></label>
                                            <input type="text" name="phone_number" id="phone_number" class="form-control " value="{{$member->user->phone_number}}" placeholder="@lang('placeholders.phone_number')" tabindex="6">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group select-form-group m-form__group">
                                            <label class="form-control-label" for="blood_group">@lang('labels.blood_group')</label>
                                            <div class="m-select2 m-select2--air">
                                                <select class="m-select2 form-control pin-blood-group" name="blood_group" id="blood_group" tabindex="7" style="width:100%;">
                                                    <option value=""></option>
                                                    @foreach(config('constant.blood_group_options') as $option)
                                                        <option value="{{$option}}" {{$member->blood_group == $option?'selected' : ''}}>{{$option}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group m-form__group">
                                            <label class="form-control-label" for="date_of_birth"> @lang('labels.date_of_birth')</label>
                                            @if($member->date_of_birth == '' || $member->date_of_birth == null)
                                                <input type="text" name="date_of_birth" autocomplete="off" class="form-control m-input--air pin-date_of_birth" value=""  tabindex="8" placeholder="@lang('placeholders.date')">
                                            @else
                                                <input type="text" name="date_of_birth" autocomplete="off" class="form-control m-input--air pin-date_of_birth" value="{{$member->date_of_birth->format('d/m/Y')}}" tabindex="8" placeholder="@lang('placeholders.date')">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group m-form__group">
                                            <label class="form-control-label" for="date_of_join"> @lang('labels.date_of_joining')</label>
                                            @if($member->date_of_join == '' || $member->date_of_join == null)
                                                <input type="text" name="date_of_join" autocomplete="off" class="form-control m-input--air pin-date_of_join" value=""  tabindex="9" data-date-end-date="0d" placeholder="@lang('placeholders.date')">
                                            @else
                                                <input type="text" name="date_of_join" autocomplete="off" class="form-control m-input--air pin-date_of_join" value="{{$member->date_of_join->format('d/m/Y')}}"  tabindex="9" data-date-end-date="0d" placeholder="@lang('placeholders.date')">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group m-form__group has-feedback">
                                    <label class="form-control-label" for="profile_photo">@lang('labels.profile_photo')</label>
                                    @if(!is_null($member->photo) && !empty($member->photo))
                                        <input type="file" name="profile_photo" id="edit_profile_photo" data-profile-photo-url="{{$member->photo->display_thumbnail_url}}" tabindex="10">
                                    @else
                                        <input type="file" name="profile_photo" id="edit_profile_photo"  tabindex="10">
                                    @endif
                                </div>
                            </div>
                        </div>
		            </div>
	            </div>
                <div class="m-portlet__foot">
                    <div class="row align-items-center" style="padding:5px 0px 5px 0px">
                        <div class="col-lg-12 text-right">
                            <button type="button" class="btn btn-brand pin-submit" id="updateProfileBtn" tabindex="11">@lang('buttons.update')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('page_specific_js')
@stop
