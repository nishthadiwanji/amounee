@extends('layouts.main')
@section('page_title')
    @lang('heading.manage_categories')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.category._partials.category-header')
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
                                    @lang('heading.manage_categories')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-info pin-submit pin-common-search-in-list-btn">@lang('buttons.search')</button> &nbsp; &nbsp;
                                <button type="button" class="btn btn-secondary pin-common-search-in-list-">@lang('buttons.refresh')</button> &nbsp; &nbsp;
                                <a href="{{route('category.export')}}" class="btn btn-success" data-redirect-url="{{route('category.index')}}"><i class="far fa-arrow-to-bottom"></i> @lang('buttons.export')</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control pin-common-search-text" placeholder="@lang('placeholders.search')" name="search" value="{{$search}}">
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
                    <!-- <div class="card-header card-header-tabs-line">
                        <div class="card-title">
                        </div>
                        <div class="card-toolbar">
                        </div>
                    </div> -->
                    <div class="card-body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <div class="pin-list-group">
                                    <div class="pin-list-item">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="category-list">
                                                    <div>
                                                        <table class="table table-bordered table-hover">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th><i style="color:#ffffff" class="fal fa-search"></i> Category</th>
                                                                    <th><i style="color:#ffffff" class="fal fa-search"></i> Sub Category</th>
                                                                    <th>Commission</th>
                                                                    <th>Updated on</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($categories as $category)
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{route('category.show',[$category->parent->id_token])}}?sub_category_token={{$category->id_token}}">
                                                                            {{$category->parent->category_name}}
                                                                        </a>                    
                                                                    </td>
                                                                    <td>{{$category->category_name}}</td>
                                                                    <td>{{$category->commission ? $category->commission."%" : "N/A"}}</td>
                                                                    <td>{{$category->updated_at->format('d/m/Y')}}</td>
                                                                    <td>
                                                                        <!-- <a href="javascript(void);" class="btn btn-outline-info">Commission</a> -->
                                                                        <button type="button" class="btn btn-outline-info btn-sm commission-btn" data-name="{{$category->category_name}}" data-toggle="modal" data-target="#commissionModal" data-action="{{route('category.storeCommission',[$category->id_token])}}">@lang('buttons.commission')</button>
                                                                    </td>
                                                                </tr>
                                                                @empty
                                                                <tr style="padding-top:40px;">
                                                                    <td colspan='5'>
                                                                        <div class="text-center m--font-brand">
                                                                            <i class='fa fa-exclamation-triangle fa-fw'></i> No categories found.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__foot m-portlet__foot--fit">
                        <div class="m-form__actions">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-2" style="padding-top:10px;padding-bottom:10px;">
                                    <div class="m-select2 m-select2--air">
                                        <select class="form-control m-select2 pin-common-records" name="records" data-page="{{$categories->currentPage()}}">
                                            @foreach(config('constant.LIMIT_ARRAY') as $val)
                                            <option value={{$val}} {{$records == $val ? 'selected' : ''}}>{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-10" style="padding-top:10px;padding-bottom:10px;">
                                    {!! $categories->appends(['records'=>$records,'search'=>$search])->render() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="commissionModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form class="m-form m-form--fit" id="commissionForm" data-redirect-url="{{route('category.index')}}" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Commission for <span class="m--font-brand" id="catname" style="word-break: break-all;"></span></h4>
                </div>
                <div class="modal-body">

                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group m-form__group has-feedback">
                                        <input type="text" id="commission" name="commission" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-1 col-12">
                                    <div class="form-group m-form__group has-feedback">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="margin-left:-33px;"> % </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn btn-success pin-submit pin-common-submit">
                        @lang('buttons.update')
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