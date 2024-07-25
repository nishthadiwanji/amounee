<div class="pin-list-item">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="payment-list">
                <div>
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th><i style="color:#ffffff" class="fal fa-search"></i>Artisan Name</th>
                                <th>Transaction ID</th>
                                <th>Payment Amount</th>
                                <th><i style="color:#ffffff" class="fal fa-search"></i>Payment Type</th>
                                <th>Paid Amount</th>
                                <th>Date of Payment</th>
                                <th>Deleted By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <a href="{{route('payment.show',[$payment->id_token])}}">
                                        {{$payment->artisan->fullName()}}
                                    </a>
                                </td>
                                <td>{{$payment->getTransactionId()}}</td>
                                <td>{{number_format($payment->paid_amount)}}</td>
                                <td>{{$payment->payment_type}}</td>
                                <td>{{number_format($payment->paid_amount ?? 0)}}</td>
                                <td>{{is_null($payment->date_payment) ? 'N/A' : $payment->date_payment->format('d/m/Y')}}</td>
                                <td>
                                    {{$payment->deletedBy->full_name()}}
                                </td>
                            </tr>
                            @empty
                            <tr style="padding-top:40px;">
                                <td colspan='7'>
                                    <div class="text-center m--font-brand">
                                        <i class='fa fa-exclamation-triangle fa-fw'></i> @lang('messages.empty_payment_msg')
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