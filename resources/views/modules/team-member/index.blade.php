@extends('layouts.main')
@section('page_title')
@lang('heading.manage_members')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.team-member._partials.team-members-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form>
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="fas fa-tasks"></i>
                                </span>
                                <h3 class="card-label">
                                    @lang('heading.manage_members')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-info pin-submit pin-complex-search-in-list-btn">@lang('buttons.search')</button> &nbsp; &nbsp;
                                <button type="button" class="btn btn-secondary pin-complex-search-in-list-refresh">@lang('buttons.refresh')</button> &nbsp; &nbsp;
                                <!-- <button type="button" class="btn btn-success pin-complex-search-in-list-export"><i class="far fa-arrow-to-bottom"></i> @lang('buttons.export')</button> -->
                                <a href="{{route('team-member.export')}}" class="btn btn-success" data-redirect-url="{{route('team-member.index')}}"><i class="far fa-arrow-to-bottom"></i> @lang('buttons.export')</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control " placeholder="@lang('placeholders.search')" name="search" value="{{$search}}">
                                        <input type="hidden" name="status" value="{{$status}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <br>
                <div class="card card-custom">
                    <div class="card-header card-header-tabs-line">
                        <div class="card-title">
                        </div>
                        <div class="card-toolbar">
                            <ul class="nav nav-tabs nav-bold nav-tabs-line team-member-tabview" role="tablist" data-status="{{$status}}">
                                <li class="nav-item" onclick="window.open('{{route('team-member.index',['records'=>$records,'status'=>'active','search'=>$search])}}','_self')">
                                    <a class="nav-link {{$status == 'active' ? 'active' : '' }}" data-toggle="tab" href="#" role="tab">
                                        <i class="fa fa-check fa-fw m--font-success"></i>&nbsp;
                                        @lang('labels.active')
                                    </a>
                                </li>
                                <li class="nav-item" onclick="window.open('{{route('team-member.index',['records'=>$records,'status'=>'banned','search'=>$search])}}','_self')">
                                    <a class="nav-link {{$status == 'banned' ? 'active' : '' }}" data-toggle="tab" href="#" role="tab">
                                        <i class="fa fa-ban fa-fw m--font-danger"></i>&nbsp;
                                        @lang('labels.banned')
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <div class="pin-list-group">
                                    @if($status == 'banned')
                                    @forelse($members as $member)
                                    @include('modules.team-member._partials.banned-team-member-row',['members'=>$member])
                                    @empty
                                    <div class="row" style="padding-top:40px;">
                                        <div class="col-12 text-center m--font-brand">
                                            <i class='fa fa-exclamation-triangle fa-fw'></i> @lang('messages.empty_banned_member_msg')
                                        </div>
                                    </div>
                                    @endforelse
                                    @else
                                    @forelse($members as $member)
                                    @include('modules.team-member._partials.active-team-member-row',['member'=>$member])
                                    @empty
                                    <div class="row" style="padding-top:40px;">
                                        <div class="col-12 text-center m--font-brand">
                                            <i class='fa fa-exclamation-triangle fa-fw'></i> @lang('messages.empty_active_member_msg')
                                        </div>
                                    </div>
                                    @endforelse
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-2" style="padding-top:10px;padding-bottom:10px;">
                                    <div class="m-select2 m-select2--air">
                                        <select class="form-control m-select2 pin-complex-records" name="records" data-page="{{$members->currentPage()}}">
                                            @foreach(config('constant.LIMIT_ARRAY') as $val)
                                            <option value={{$val}} {{$records == $val ? 'selected' : ''}}>{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-10" style="padding-top:10px;padding-bottom:10px;">
                                    {!! $members->appends(['records'=>$records,'status'=>$status,'search'=>$search])->render() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partial.generate-password')
@stop
@section('page_specific_js')
@stop