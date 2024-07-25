@extends('layouts.main')
@section('page_title')
{{$payment->artisan->fullName()}} | @lang('heading.payment')
@stop
@section('page_specific_css')
@stop
@section('page_specific_body')
@include('modules.payment._partials.payment-header')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title">
                            <span class="card-icon">
                                <i class="fas fa-info"></i>
                            </span>
                            <h3 style="word-break: break-all;" class="card-label">
                                Details of {{strtoupper($payment->artisan->fullName())}}'s Payment
                            </h3>
                        </div>
                    </div>

                    <div class="card-body" style="padding: 1rem 1rem;">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <h6> <i class="fas fa-user"></i> @lang('labels.artisan_name')</h6>
                                <span><strong class="m--font-brand">{{$payment->artisan->fullName()}}</strong></span>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-3 col-12">
                                <h6><i class="fas fa-rupee-sign"></i> @lang('labels.payment_amount')</h6>
                                <span><strong class="m--font-brand">{{number_formatter($payment->payment_amount)}}</strong></span>
                            </div>
                            <div class="col-md-3 col-12">
                                <h6><i class="fas fa-money-bill-alt"></i> @lang('labels.payment_type')</h6>
                                <span><strong class="m--font-brand">{{$payment->payment_type}}</strong></span>
                            </div>
                            <div class="col-md-3 col-12">
                                <h6><i class="fal fa-donate"></i> @lang('labels.paid_amount')</h6>
                                <span><strong class="m--font-brand">{{number_formatter($payment->paid_amount ?? 0)}}</strong></span>
                            </div>
                            <div class="col-md-3 col-12">
                                <h6><i class="fal fa-calendar-alt"></i> @lang('labels.date_payment')</h6>
                                <span><strong class="m--font-brand">{{!is_null($payment->date_payment) ? $payment->date_payment->format('d/m/Y') : 'N/A'}}</strong></span>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h6><i class="fas fa-sticky-note"></i> @lang('labels.note')</h6>
                                <span><strong class="m--font-brand">{{$payment->note ?? 'N/A'}}</strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col-lg-12 text-right">
                                <a href="{{route('payment.index')}}" class="btn btn-warning">
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