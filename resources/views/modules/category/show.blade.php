@extends('layouts.main')
@section('page_title')
{{$category->first_name}} | @lang('heading.category')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.category._partials.category-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h2 style="word-break: break-all;" class="card-label">
                        {{strtoupper($category->category_name)}}
                    </h2>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <h6>@lang('labels.category')</h6>
                        <span><strong class="m--font-brand">{{$category->category_name}}</strong></span>
                    </div>
                    <div class="col-md-4 col-12">
                        <h6>@lang('labels.sub_category')</h6>
                        <span><strong class="m--font-brand">{{$sub_category->category_name}}</strong></span>
                    </div>
                    <div class="col-md-4 col-12">
                        <h6>@lang('labels.commission')</h6>
                        <span><strong class="m--font-brand">{{$sub_category->commission}}</strong></span>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>

                <div class="row">
                    <div class="d-flex flex-column-fluid">
                        <div class="container">
                            <div class="row pin-widget">
                                <div class="col-md-5" >
                                    <h6>Commission History</h6>
                                    <hr>
                                    <div class="scroll" style="overflow-y:auto; height:200px;">
                                        <table class="table table-bordered table-hover">
                                            <col style="width:50%;">
                                            <col style="width:50%;">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th style="position:sticky; top:0;">Updated By:</th>
                                                <th style="position:sticky; top:0;">Updated On:</th>
                                                <th style="position:sticky; top:0;">Commission</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($sub_category->commissions()->get() as $commissionhistory)
                                                <tr>
                                                    <td>{{$commissionhistory->user->full_name()}}</td>
                                                    <td>
                                                        {{ $commissionhistory->updated_at }}
                                                    </td>
                                                    <td>{{$commissionhistory->commission}}</td>
                                                </tr>
                                                @empty
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-5" > 
                                    <h6>Craft Practiced</h6>
                                    <hr>
                                    <div class="scroll" style="overflow-y:auto; height:200px;">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th style="position:sticky; top:0;">Artisan Name</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($category->artisans()->get() as $artisan)
                                                <tr>
                                                    <td>
                                                    <a href="{{route('artisan.show',[$artisan->id_token])}}">{{$artisan->first_name}} {{$artisan->last_name}}</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <div class="text-center m--font-brand">
                                                        <i class='fa fa-exclamation-triangle fa-fw'></i> Craft practiced by none!
                                                    </div>
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

                <br>
                <br>
            </div>

            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-lg-12 text-right">
                        <a href="{{route('category.index')}}" class="btn btn-warning">
                            @lang('buttons.back')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page_specific_js')
@stop