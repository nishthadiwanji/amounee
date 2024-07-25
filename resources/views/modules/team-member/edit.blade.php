@extends('layouts.main')
@section('page_title')
@lang('heading.edit_member')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.team-member._partials.team-members-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="m-form m-form--fit" id="TeamMemberForm" name="TeamMemberForm" data-action="{{route('team-member.update',[$member->id_token])}}" data-redirect-url="{{route('team-member.index')}}" onsubmit="return false;">
                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="fas fa-pencil"></i>
                                </span>
                                <h3 class="card-label font-brand">
                                    @lang('heading.edit_member')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <input type="hidden" value="PUT" name="_method">
                                <button type="button" class="btn btn-success pin-submit pin-fwfile-update">@lang('buttons.update')</button>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group m-form__group has-feedback">
                                        <label class="form-control-label" for="profile_photo">@lang('labels.profile_photo')</label>
                                        @if(!is_null($member->photo) && !empty($member->photo))

                                        <input type="file" name="profile_photo" id="edit_profile_photo" style="max-height:100%; max-width:100%;" class="form-files" data-profile-photo-url="{{$member->photo->display_thumbnail_url}}" tabindex="1">
                                        @else
                                        <input type="file" name="profile_photo" id="edit_profile_photo" style="max-height:100%; max-width:100%;" class="form-files" tabindex="1">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-9 col-12">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group m-form__group">
                                                <label class="form-control-label" for="first_name">@lang('labels.first_name')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="first_name" class="form-control " value="{{$member->user->first_name}}" placeholder="@lang('placeholders.first_name')">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group m-form__group">
                                                <label class="form-control-label" for="middle_name">@lang('labels.middle_name')</label>
                                                <input type="text" name="middle_name" class="form-control " value="{{$member->user->middle_name}}" placeholder="@lang('placeholders.middle_name')">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group m-form__group">
                                                <label class="form-control-label" for="last_name">@lang('labels.last_name')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="last_name" class="form-control " value="{{$member->user->last_name}}" placeholder="@lang('placeholders.last_name')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group select-form-group m-form__group">
                                                <label class="form-control-label" for="employee_id">@lang('labels.emp_id')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="employee_id" class="form-control " value="{{$member->employee_id}}" placeholder="@lang('placeholders.emp_id')">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group m-form__group">
                                                <label class="form-control-label" for="email">@lang('labels.email_address')&nbsp;<span class="required">*</span></label>
                                                <input type="email" name="email" id="email" class="form-control " value="{{$member->user->email}}" placeholder="@lang('placeholders.email_address')">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-3">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="country_code">@lang('labels.country_code')</label>
                                                <input type="text" name="country_code" class="form-control " placeholder="@lang('placeholders.country_code')" value="{{$member->user->country_code}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-9">
                                            <div class="form-group m-form__group">
                                                <label class="form-control-label" for="phone_number">@lang('labels.phone_number')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="phone_number" id="phone_number" class="form-control " value="{{$member->user->phone_number}}" placeholder="@lang('placeholders.phone_number')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group select-form-group m-form__group">
                                                <label class="form-control-label" for="department_id">@lang('labels.department')&nbsp;<span class="required">*</span></label>
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-department" name="department" style="width:100%;" data-placeholder="Select a Department">
                                                        <option value=""></option>
                                                        @foreach($departments as $department)
                                                        <option value="{{$department}}" {{$member->department == $department ? 'selected' : ''}}>{{$department}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group select-form-group m-form__group">
                                                <label class="form-control-label" for="designation_id">@lang('labels.designation')&nbsp;<span class="required">*</span></label>
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-designation" name="designation" style="width:100%;" data-placeholder="Select a Designation">
                                                        <option value=""></option>
                                                        @foreach($designations as $designation)
                                                        <option value="{{$designation}}" {{$member->designation == $designation ? 'selected' : ''}}>{{$designation}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group select-form-group m-form__group">
                                                <label class="form-control-label" for="blood_group">@lang('labels.blood_group')</label>
                                                <div class="m-select2 m-select2--air">
                                                    <select class="form-control m-select2 pin-blood_group" name="blood_group" style="width:100%;" data-placeholder="Select a Blood Group">
                                                        <option value=""></option>
                                                        @foreach($blood_groups as $blood_group)
                                                        <option value="{{$blood_group}}" {{$member->blood_group == $blood_group ? 'selected' : ''}}>{{$blood_group}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group select-form-group">
                                                <label for="dob" class="form-control-label">@lang('labels.dob')&nbsp;</label>
                                                <input type="text" name="dob" class="form-control text-muted pin-datepicker" placeholder="dd/mm/yyyy" value="{{is_null($member->doj) ? '' : $member->dob->format('d/m/Y')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group select-form-group">
                                                <label for="doj" class="form-control-label">@lang('labels.doj')&nbsp;</label>
                                                <input type="text" name="doj" class="form-control text-muted pin-datepicker" placeholder="dd/mm/yyyy" value="{{is_null($member->doj) ? '' : $member->doj->format('d/m/Y')}}">
                                            </div>
                                        </div>
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
@section('page_specific_js')
@stop