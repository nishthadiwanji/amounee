@extends('layouts.main')
@section('page_title')
@lang('heading.add_payment')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.payment._partials.payment-header')

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="form" id="PaymentForm" name="PaymentForm" action="{{route('payment.store')}}" data-redirect-url="{{route('payment.create')}}" onsubmit="return false;">
                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="la la-user-plus"></i>
                                </span>
                                <h3 class="card-label font-brand">
                                    @lang('heading.add_payment')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-success pin-common-submit">
                                    @lang('buttons.save')
                                </button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-info m-btn--air pin-common-reset">
                                    @lang('buttons.reset')
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="artisan_id">@lang('labels.artisan_name')&nbsp;<span class="required">*</span></label>
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-category" name="artisan_id" tabindex="10" style="width:100%;" data-placeholder="Select Artisan">
                                                <option></option>
                                                @foreach($artisans as $artisan)
                                                <option value="{{$artisan->id_token}}">
                                                    {{$artisan->fullName()}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="payment_amount">@lang('labels.payment_amount')&nbsp;<span class="required">*</span></label>
                                        <input type="text" name="payment_amount" id="payment_amount" class="form-control" placeholder="@lang('placeholders.payment_amount')">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="payment_type">@lang('labels.payment_type')&nbsp;<span class="required">*</span></label>
                                        <input type="text" name="payment_type" class="form-control " placeholder="@lang('placeholders.payment_type')">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="paid_amount">@lang('labels.paid_amount')&nbsp;</label>
                                        <input type="text" name="paid_amount" class="form-control " placeholder="@lang('placeholders.paid_amount')">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="date_payment">@lang('labels.date_payment')&nbsp;</label>
                                        <input type="text" name="date_payment" class="form-control pin-datepicker" placeholder="@lang('placeholders.date')" data-orientation="bottom">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label for="note" class="form-control-label">@lang('labels.note')&nbsp;</label>
                                    <textarea name="note" class="form-control" id="note" placeholder="@lang('placeholders.note')" rows="10" style="resize:none;"></textarea>
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