@extends('layouts.main')
@section('page_title')
@lang('heading.manage_payments')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.payment._partials.payment-header')
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
                                    @lang('heading.manage_payments')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-info pin-submit pin-complex-search-in-list-btn">@lang('buttons.search')</button> &nbsp; &nbsp;
                                <button type="button" class="btn btn-secondary pin-complex-search-in-list-refresh">@lang('buttons.refresh')</button> &nbsp; &nbsp;
                                <!-- <button type="button" class="btn btn-success pin-complex-search-in-list-export"><i class="far fa-arrow-to-bottom"></i> @lang('buttons.export')</button> -->
                                <a href="{{route('payment.export')}}" class="btn btn-success" data-redirect-url="{{route('payment.index')}}"><i class="far fa-arrow-to-bottom"></i> @lang('buttons.export')</a>
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
                            <ul class="nav nav-tabs nav-bold nav-tabs-line payment-tabview" role="tablist" data-status="{{$status}}">
                                <li class="nav-item" onclick="window.open('{{route('payment.index',['records'=>$records,'status'=>'active','search'=>$search])}}','_self')">
                                    <a class="nav-link {{$status == 'active' ? 'active' : '' }}" data-toggle="tab" href="#"  role="tab">
                                        <i class="fa fa-check fa-fw m--font-success"></i>&nbsp;
                                        @lang('labels.active')
                                    </a>
                                </li>
                                <li class="nav-item" onclick="window.open('{{route('payment.index',['records'=>$records,'status'=>'deleted','search'=>$search])}}','_self')">
                                    <a class="nav-link {{$status == 'deleted' ? 'active' : '' }}" data-toggle="tab" href="#"  role="tab">
                                        <i class="fa fa-ban fa-fw m--font-danger"></i>&nbsp;
                                        @lang('labels.deleted')
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <div class="pin-list-group">
                                    @if($status == 'deleted')
                                        @include('modules.payment._partials.deleted-payment-row',['payments'=>$payments])
                                    @else
                                        @include('modules.payment._partials.active-payment-row',['payments'=>$payments])
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
                                        <select class="form-control m-select2 pin-complex-records" name="records" data-page="{{$payments->currentPage()}}">
                                            @foreach(config('constant.LIMIT_ARRAY') as $val)
                                                <option value={{$val}} {{$records == $val ? 'selected' : ''}}>{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-10" style="padding-top:10px;padding-bottom:10px;">
                                    {!! $payments->appends(['records'=>$records,'status'=>$status,'search'=>$search])->render() !!}
                                </div>
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
