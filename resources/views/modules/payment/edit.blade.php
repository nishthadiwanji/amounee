@extends('layouts.main')
@section('page_title')
@lang('heading.edit_payment')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.artisan._partials.artisan-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form class="m-form m-form--fit" id="PaymentForm" name="PaymentForm" action="{{route('payment.update',[$payment['id_token']])}}" data-redirect-url="{{route('payment.index')}}" onsubmit="return false;">
                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="fas fa-pencil"></i>
                                </span>
                                <h3 class="card-label font-brand">
                                    @lang('heading.edit_payment')
                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-primary pin-common-update">@lang('buttons.update')</button>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="artisan_name">@lang('labels.artisan_name')&nbsp;<span class="required">*</span></label>
                                        <div class="m-select2 m-select2--air">
                                            <select class="form-control m-select2 pin-category" name="artisan_id" tabindex="10" style="width:100%;" data-placeholder="Select Artisan">
                                                <option></option>
                                                @foreach($artisans as $artisan)
                                                <option value="{{$artisan->id_token}}" {{ $payment->artisan_id == $artisan->id ? 'selected' : '' }}>
                                                    {{$artisan->fullName()}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group m-form__group">
                                        <label class="form-control-label" for="payment_amount">@lang('labels.payment_amount')&nbsp;<span class="required">*</span></label>
                                        <input type="text" name="payment_amount" id="payment_amount" class="form-control " value="{{$payment->payment_amount}}" placeholder="@lang('placeholders.payment_amount')">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="payment_type">@lang('labels.payment_type')&nbsp;<span class="required">*</span></label>
                                        <input type="text" name="payment_type" class="form-control " value="{{$payment->payment_type}}" placeholder="@lang('placeholders.payment_type')">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="paid_amount">@lang('labels.paid_amount')&nbsp;</label>
                                        <input type="text" name="paid_amount" class="form-control " value="{{$payment->paid_amount}}" placeholder="@lang('placeholders.paid_amount')">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group ">
                                        <label class="form-control-label" for="date_payment">@lang('labels.date_payment')&nbsp;</label>
                                        <input type="text" name="date_payment" class="form-control pin-datepicker" value="{{!is_null($payment->date_payment) ? $payment->date_payment->format('d/m/Y') : ''}}" placeholder="@lang('placeholders.date')">
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <textarea name="note" class="form-control" id="note" placeholder="@lang('placeholders.note')" rows="10">{{$payment->note}}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('page_specific_js')
@stop