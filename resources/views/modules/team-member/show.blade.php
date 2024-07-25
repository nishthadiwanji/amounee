@extends('layouts.main')
@section('page_title')
{{$member->user->name()}} | @lang('heading.member')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.team-member._partials.team-members-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 style="word-break: break-all;" class="card-label">
                                {{strtoupper($member->user->name())}}
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 col-12">
                                <img src="{{empty($member->photo) ? asset('img/no_image.jpg') :$member->photo->display_thumbnail_url}}" alt="No Image" height="150" width="150">
                            </div>
                            <div class="col-md-1 col-12"><br></div>
                            <div class="col-md-9 col-12">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <h6><i class='fas fa-mobile fa-fw'></i> @lang('labels.phone_number')</h6>
                                        <span><strong class="m--font-brand">{{$member->user->full_phone_number()}}</strong></span>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <h6><i class='fas fa-at fa-fw'></i> @lang('labels.email_address')</h6>
                                        <span class="overflow-visible"><strong class="m--font-brand">{{$member->user->email}}</strong></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12"><br></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <h6><i class='far fa-portrait fa-fw'></i> @lang('labels.designation')</h6>
                                        <span><strong class="m--font-brand">{{$member->designation}}</strong></span>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <h6><i class="fal fa-tint"></i> @lang('labels.blood_group')</h6>
                                        <span class="overflow-visible"><strong class="m--font-brand">{{$member->blood_group?$member->blood_group:"N/A"}}</strong></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12"><br></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <h6><i class="fal fa-calendar-alt"></i> @lang('labels.dob')</h6>
                                        <span><strong class="m--font-brand">{{ !is_null($member->dob) ? $member->dob->format('d/m/Y'): "N/A"}}</strong></span>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <h6><i class="fal fa-calendar-alt"></i> @lang('labels.doj')</h6>
                                        <span class="overflow-visible"><strong class="m--font-brand">{{ !is_null($member->doj) ? $member->doj->format('d/m/Y') : "N/A"}}</strong></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12"><br></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <h6><i class='fas fa-briefcase fa-fw'></i> @lang('labels.department')</h6>
                                        <span><strong class="m--font-brand">{{$member->department}}</strong></span>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <h6><i class='far fa-portrait fa-fw'></i> @lang('labels.emp_id')</h6>
                                        <span><strong class="m--font-brand">{{$member->employee_id}}</strong></span>
                                    </div>
                                    <div class="col-md-4">
                                        <h6><i class='fas fa-sign-in-alt fa-fw'></i> @lang('labels.last_login')</h6>
                                        <span><strong class="m--font-brand">{{!empty($member->user->last_login) ? \Carbon\Carbon::parse($member->user->last_login)->format('d/m/Y H:i:s') : 'Not Available' }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col-lg-12 text-right">
                                <a href="{{route('team-member.index')}}" class="btn btn-info">
                                    @lang('buttons.back')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page_specific_js')
@stop
