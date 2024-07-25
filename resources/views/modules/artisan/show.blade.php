@extends('layouts.main')
@section('page_title')
{{$artisan->first_name}} | @lang('heading.artisan')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.artisan._partials.artisan-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h2 style="word-break: break-all;" class="card-label">
                        {{strtoupper($artisan->first_name)}} {{strtoupper($artisan->last_name)}}
                    </h2>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 col-12">
                        <h6>@lang('labels.vendor_picture')</h6>
                        <img src="{{empty($artisan->photo()) ? asset('img/no_image.jpg') : $artisan->photo->display_thumbnail_url}}" alt="No Image" height="150" width="150">
                    </div>
                    <div class="col-md-1 col-12"><br></div>
                    <div class="col-md-9 col-12">
                        <br>
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <h6><i class="fas fa-user"></i> @lang('labels.name')</h6>
                                <span><strong class="m--font-brand">{{$artisan->first_name}} {{$artisan->last_name}}</strong></span>
                            </div>
                            <div class="col-md-4 col-12">
                                <h6><i class="fas fa-store"></i> @lang('labels.trade_name')</h6>
                                <span><strong class="m--font-brand">{{$artisan->trade_name}}</strong></span>
                            </div>
                            <div class="col-md-4 col-12">
                                <h6><i class="fal fa-percent"></i> @lang('labels.gst')</h6>
                                <span><strong class="m--font-brand">{{$artisan->gst ? $artisan->gst: "N/A"}}</strong></span>
                            </div>
                        </div>
                        <br>
                        <br>

                        <div class="row">
                            <div class="col-md-4 col-12">
                                <h6><i class="fas fa-phone-alt"></i> @lang('labels.phone_number')</h6>
                                <span><strong class="m--font-brand">{{$artisan->country_code}}&nbsp;&nbsp;{{$artisan->phone_number}}</strong></span>
                            </div>
                            <div class="col-md-4 col-12">
                                <h6><i class="far fa-at"></i> @lang('labels.email')</h6>
                                <span><strong class="m--font-brand">{{$artisan->email}}</strong></span>
                            </div>
                            <div class="col-md-4 col-12">
                                <h6><i class="fab fa-cotton-bureau"></i> @lang('labels.craft_category')</h6>
                                <span><strong class="m--font-brand">{{$artisan->category->category_name}}</strong></span>
                            </div>
                        </div>
                        <br>
                        <br>
                        
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <h6><i class="far fa-sack-dollar"></i> @lang('labels.commission')</h6>
                                <span><strong class="m--font-brand">{{$artisan->commission}}</strong></span>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
                <br>
                <br>

                <h6>Address: <span class="required">*</span></h6>
                <hr>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <h6><i class="fal fa-compass"></i> @lang('labels.street1')</h6>
                        <span><strong class="m--font-brand">{{$artisan->street1}}</strong></span>
                    </div>
                    <div class="col-md-4 col-12">
                        <h6><i class="fal fa-compass"></i> @lang('labels.street2')</h6>
                        <span><strong class="m--font-brand">{{$artisan->street2}}</strong></span>
                    </div>
                    <div class="col-md-4 col-12">
                        <h6><i class="fas fa-map-pin"></i> @lang('labels.zip')</h6>
                        <span><strong class="m--font-brand">{{$artisan->zip}}</strong></span>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-4 col-12">
                        <h6><i class="fal fa-map-marker-alt"></i> @lang('labels.city')</h6>
                        <span><strong class="m--font-brand">{{$artisan->city}}</strong></span>
                    </div>
                    <div class="col-md-4 col-12">
                        <h6><i class="far fa-map-marked"></i> @lang('labels.state')</h6>
                        <span><strong class="m--font-brand">{{$artisan->state}}</strong></span>
                    </div>
                    <div class="col-md-4 col-12">
                        <h6><i class="far fa-globe-asia"></i> @lang('labels.country')</h6>
                        <span><strong class="m--font-brand">{{$artisan->country}}</strong></span>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-4 col-12">
                        <h6><i class="fal fa-trophy" aria-hidden="true"></i> @lang('labels.awards')</h6>
                        <span><strong class="m--font-brand">{{$artisan->awards ? $artisan->awards : "N/A"}}</strong></span>
                    </div>
                </div>
                <br>
                <br>

                <h6>Payment Option: <span class="required">*</span></h6>
                <hr>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <h6><i class="far fa-university"></i> @lang('labels.account_name')</h6>
                        <span><strong class="m--font-brand">{{$artisan->account_name}}</strong></span>
                    </div>
                    <div class="col-md-4 col-12">
                        <h6><i class="fal fa-file-invoice"></i> @lang('labels.account_number')</h6>
                        <span><strong class="m--font-brand">{{$artisan->account_number}}</strong></span>
                    </div>
                    <div class="col-md-4 col-12">
                        <h6><i class="fal fa-university"></i> @lang('labels.bank_name')</h6>
                        <span><strong class="m--font-brand">{{$artisan->bank_name}}</strong></span>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-4 col-12">
                        <h6><i class="fal fa-money-check"></i> @lang('labels.ifsc')</h6>
                        <span><strong class="m--font-brand">{{$artisan->ifsc}}</strong></span>
                    </div>
                </div>
                <br>
                <br>
                @if($artisan->approval_note)
                <div class="row">
                    <div class="col-md-4 col-12">
                        <h6><i class="far fa-sticky-note"></i> @lang('labels.approval_note')</h6>
                        <span><strong class="m--font-brand">{{$artisan->approval_note}}</strong></span>
                    </div>
                </div>
                @endif
                @if($artisan->rejection_note)
                <div class="row">
                    <div class="col-md-4 col-12">
                        <h6><i class="far fa-sticky-note"></i> @lang('labels.rejection_note')</h6>
                        <span><strong class="m--font-brand">{{$artisan->rejection_note}}</strong></span>
                    </div>
                </div>
                @endif
                <br>
        <br>
        @include('modules.artisan._partials.artisan-files')
        <br>
        <br>
        @include('modules.artisan._partials.artisan-products')
        <br>
        <br>
        @include('modules.artisan._partials.artisan-status')
    </div>

    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-lg-12 text-right">
                <a href="{{route('artisan.index')}}" class="btn btn-warning">
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