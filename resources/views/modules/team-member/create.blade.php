@extends('layouts.main')
@section('page_title')
@lang('heading.add_member')
@stop @section('page_specific_css')
@stop @section('page_specific_body')
@include('modules.team-member._partials.team-members-header')

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="form" id="TeamMemberForm" name="TeamMemberForm" data-action="{{route('team-member.store')}}" data-redirect-url="{{route('team-member.create')}}" onsubmit="return false;">
                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="la la-user-plus"></i>
                                </span>
                                <h3 class="card-label font-brand">
                                    @lang('heading.add_member')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" id="addTeamMemberBtn" class="btn btn-success pin-submit pin-fwfile-submit">
                                    @lang('buttons.save')
                                </button>&nbsp;&nbsp;
                                <button type="reset" id="addTeamMemberResetBtn" class="btn btn-info m-btn--air pin-common-reset">
                                    @lang('buttons.reset')
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="profile_photo">@lang('labels.profile_photo')</label>
                                        <input type="file" name="profile_photo" id="profile_photo" class="form-files">
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
                                                <label class="form-control-label" for="middle_name">@lang('labels.middle_name')</label>
                                                <input type="text" name="middle_name" class="form-control " placeholder="@lang('placeholders.middle_name')">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="last_name">@lang('labels.last_name')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="last_name" class="form-control " placeholder="@lang('placeholders.last_name')">
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
                                        <div class="col-md-4 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="phone_number">@lang('labels.phone_number')<span class="required">*</span></label>
                                                <input type="text" name="phone_number" class="form-control " placeholder="@lang('placeholders.phone_number')">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="email">@lang('labels.email_address')&nbsp;<span class="required">*</span></label>
                                                <input type="email" name="email" class="form-control " placeholder="@lang('placeholders.email_address')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="employee_id">@lang('labels.emp_id')&nbsp;<span class="required">*</span></label>
                                                <input type="text" name="employee_id" class="form-control" placeholder="@lang('placeholders.emp_id')">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="password">@lang('labels.password')&nbsp;<span class="required">*</span></label>
                                                <input type="password" name="password" id="password" class="form-control " placeholder="@lang('placeholders.password')">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="confirm_password">@lang('labels.confirm_password')&nbsp;<span class="required">*</span></label>
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control " placeholder="@lang('placeholders.confirm_password')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding: 1rem 1rem;">
                                <div class="col-md-4 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="department_id">@lang('labels.department')&nbsp;<span class="required">*</span></label>
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-department" name="department" style="width:100%;" data-placeholder="Select a Department">
                                                <option></option>
                                                @foreach($departments as $department)
                                                <option value="{{$department}}">{{$department}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="designation_id">@lang('labels.designation')&nbsp;<span class="required">*</span></label>
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-designation" name="designation" style="width:100%;" data-placeholder="Select a Designation">
                                                <option></option>
                                                @foreach($designations as $designation)
                                                <option value="{{$designation}}">{{$designation}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="blood_group">@lang('labels.blood_group')&nbsp;</label>
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-bood_group" name="blood_group" style="width:100%;" data-placeholder="Select Blood Group">
                                                <option></option>
                                                @foreach($blood_groups as $blood_group)
                                                <option value="{{$blood_group}}">{{$blood_group}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="padding: 1rem 1rem;">
                                <div class="col-md-4 col-12">
                                    <div class="form-group select-form-group">
                                        <label for="dob" class="form-control-label">@lang('labels.dob')&nbsp;</label>
                                        <input type="text" name="dob" class="form-control text-muted pin-datepicker" placeholder="dd/mm/yyyy" data-date-orientation="top" data-date-end-date="-18y">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group select-form-group">
                                        <label for="doj" class="form-control-label">@lang('labels.doj')&nbsp;</label>
                                        <input type="text" name="doj" class="form-control text-muted pin-datepicker" placeholder="dd/mm/yyyy" data-date-orientation="top" data-date-end-date="+1m">
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
@stop @section('page_specific_js')

<script>
</script>


@stop