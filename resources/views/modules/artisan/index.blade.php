@extends('layouts.main')
@section('page_title')
@lang('heading.manage_artisans')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.artisan._partials.artisan-header')
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
                                    @lang('heading.manage_artisans')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-info pin-submit pin-complex-search-in-list-btn">@lang('buttons.search')</button> &nbsp; &nbsp;
                                <button type="button" class="btn btn-secondary pin-complex-search-in-list-refresh">@lang('buttons.refresh')</button> &nbsp; &nbsp;
                                <a href="{{route('artisan.export')}}" class="btn btn-success" data-redirect-url="{{route('artisan.index')}}"><i class="far fa-arrow-to-bottom"></i> @lang('buttons.export')</a>&nbsp; &nbsp;
                                <button style="border-color:#C1804F;color:#C1804F" type="button" class="btn import-btn" data-toggle="modal" data-target="#importModal" data-action="{{ route('artisan.import') }}" data-redirect-url="{{ route('artisan.index') }}"><i class="fas fa-file-upload"></i>@lang('buttons.import')</button>
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
                            <ul class="nav nav-tabs nav-bold nav-tabs-line artisan-tabview" role="tablist" data-status="{{$status}}">
                                <li class="nav-item" onclick="window.open('{{route('artisan.index',['records'=>$records,'status'=>'pending','search'=>$search])}}','_self')">
                                    <a class="nav-link {{$status == 'pending' ? 'active' : '' }}" data-toggle="tab" href="#" role="tab">
                                        <i class="fal fa-hourglass-start m--font-warning "></i>&nbsp;
                                        @lang('labels.pending')
                                    </a>
                                </li>
                                <li class="nav-item" onclick="window.open('{{route('artisan.index',['records'=>$records,'status'=>'approved','search'=>$search])}}','_self')">
                                    <a class="nav-link {{$status == 'approved' ? 'active' : '' }}" data-toggle="tab" href="#" role="tab">
                                        <i class="fa fa-check fa-fw m--font-success"></i>&nbsp;
                                        @lang('labels.approved')
                                    </a>
                                </li>
                                <li class="nav-item" onclick="window.open('{{route('artisan.index',['records'=>$records,'status'=>'rejected','search'=>$search])}}','_self')">
                                    <a class="nav-link {{$status == 'rejected' ? 'active' : '' }}" data-toggle="tab" href="#" role="tab">
                                        <i class="fas fa-times"></i>&nbsp;
                                        @lang('labels.rejected')
                                    </a>
                                </li>
                                <li class="nav-item" onclick="window.open('{{route('artisan.index',['records'=>$records,'status'=>'banned','search'=>$search])}}','_self')">
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
                                        @include('modules.artisan._partials.banned-artisan-list',['artisans'=>$artisans])
                                    @elseif($status == 'approved')
                                        @include('modules.artisan._partials.approved-artisan-list',['artisans'=>$artisans])
                                    @elseif($status == 'pending')
                                        @include('modules.artisan._partials.pending-artisan-list',['artisans'=>$artisans])
                                    @elseif($status == 'rejected')
                                        @include('modules.artisan._partials.rejected-artisan-list',['artisans'=>$artisans])
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
                                        <select class="form-control m-select2 pin-complex-records" name="records" data-page="{{$artisans->currentPage()}}">
                                            @foreach(config('constant.LIMIT_ARRAY') as $val)
                                            <option value={{$val}} {{$records == $val ? 'selected' : ''}}>{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-10" style="padding-top:10px;padding-bottom:10px;">
                                    {!! $artisans->appends(['records'=>$records,'status'=>$status,'search'=>$search])->render() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="importModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form class="form" action="" enctype="multipart/form-data" method="post" id="importForm" data-redirect-url="" onsubmit="return false;">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-file-upload"></i>&nbsp;Import Artisans</h4>
                </div>
                <div class="modal-body">
 
                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="form-group m-form__group has-feedback">
                                        <label class="form-control-label" for="file">@lang('labels.choose_file')</label>
                                        <span class="required">*</span>
                                        <input type="file" id="file" name="file" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn btn-success pin-submit pin-common-submit import-artisan">
                        @lang('buttons.upload')
                    </button>
                    <button type="button" class="btn btn-default btn-outline-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
 
    </div>
</div>
@stop
@section('page_specific_js')
@stop