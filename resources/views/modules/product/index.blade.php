@extends('layouts.main')
@section('page_title')
@lang('heading.manage_products')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.product._partials.product-header')
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
                                    @lang('heading.manage_products')
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control " placeholder="@lang('placeholders.search')" name="search" value="{{$search}}">
                                        <input type="hidden" name="status" value="{{$status}}">
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group select-form-group ">
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-product" name="artisan_search" style="width:100%;" data-placeholder="Artisan Name">
                                                <option value="">Artisan</option>
                                                @foreach($artisans as $artisan)
                                                    <option value="{{$artisan->first_name}} {{$artisan->last_name}}">
                                                        {{$artisan->first_name}} {{$artisan->last_name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="status" value="{{$status}}">
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group select-form-group ">
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-product" name="stock_search" style="width:100%;" data-placeholder="Stock Status">
                                                <option value="">Stock Status</option>
                                                <option value="In stock">In Stock</option>
                                                <option value="Made to order">Made to Order</option>
                                                <option value="Stock Out">Out of Stock</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="status" value="{{$status}}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 btn-group" style="padding-bottom: 20px;">
                                    <button type="button" class="btn btn-xs btn-info pin-submit pin-complex-search-in-list-btn">@lang('buttons.search')</button>
                                    <button type="button" class="btn btn-xs btn-secondary pin-complex-search-in-list-refresh">@lang('buttons.refresh')</button> 
                                    <a href="{{route('product.export')}}" class="btn btn-xs btn-success" data-redirect-url="{{route('product.index')}}"><i class="far fa-arrow-to-bottom"></i> @lang('buttons.export')</a>
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
                                <li class="nav-item" onclick="window.open('{{route('product.index',['records'=>$records,'status'=>'pending','search'=>$search, 'stock_search'=>$stock_search, 'artisan_search'=>$artisan_search])}}','_self')">
                                    <a class="nav-link {{$status == 'pending' ? 'active' : '' }}" data-toggle="tab" href="#" role="tab">
                                        <i class="fal fa-hourglass-start m--font-warning "></i>&nbsp;
                                        @lang('labels.pending')(0{{$total_pending_products}})
                                    </a>
                                </li>
                                <li class="nav-item" onclick="window.open('{{route('product.index',['records'=>$records,'status'=>'approved','search'=>$search, 'stock_search'=>$stock_search, 'artisan_search'=>$artisan_search])}}','_self')">
                                    <a class="nav-link {{$status == 'approved' ? 'active' : '' }}" data-toggle="tab" href="#" role="tab">
                                        <i class="fa fa-check fa-fw m--font-success"></i>&nbsp;
                                        @lang('labels.approved')(0{{$total_approved_products}})
                                    </a>
                                </li>
                                <li class="nav-item" onclick="window.open('{{route('product.index',['records'=>$records,'status'=>'rejected','search'=>$search, 'stock_search'=>$stock_search, 'artisan_search'=>$artisan_search])}}','_self')">
                                    <a class="nav-link {{$status == 'rejected' ? 'active' : '' }}" data-toggle="tab" href="#" role="tab">
                                        <i class="fas fa-times"></i>&nbsp;
                                        @lang('labels.rejected')(0{{$total_rejected_products}})
                                    </a>
                                </li>
                                <li class="nav-item" onclick="window.open('{{route('product.index',['records'=>$records,'status'=>'delisted','search'=>$search, 'stock_search'=>$stock_search, 'artisan_search'=>$artisan_search])}}','_self')">
                                    <a class="nav-link {{$status == 'delisted' ? 'active' : '' }}" data-toggle="tab" href="#" role="tab">
                                        <i class="fa fa-ban fa-fw m--font-danger"></i>&nbsp;
                                        @lang('labels.delisted')(0{{$total_delisted_products}})
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="m-section">
                            <div class="m-section__content">
                                <div class="pin-list-group">
                                    @if($status == 'delisted')
                                        @include('modules.product._partials.delisted-product-list',['products'=>$products])
                                    @elseif($status == 'approved')
                                        @include('modules.product._partials.approved-product-list',['products'=>$products])
                                    @elseif($status == 'pending')
                                        @include('modules.product._partials.pending-product-list',['products'=>$products])
                                    @elseif($status == 'rejected')
                                        @include('modules.product._partials.rejected-product-list',['products'=>$products])
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
                                        <select class="form-control m-select2 pin-complex-records" name="records" data-page="{{$products->currentPage()}}">
                                            @foreach(config('constant.LIMIT_ARRAY') as $val)
                                            <option value={{$val}} {{$records == $val ? 'selected' : ''}}>{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-10" style="padding-top:10px;padding-bottom:10px;">
                                    {!! $products->appends(['records'=>$records,'status'=>$status,'search'=>$search,'stock_search'=>$stock_search,'artisan_search'=>$artisan_search])->render() !!}
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