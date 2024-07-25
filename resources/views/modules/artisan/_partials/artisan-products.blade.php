<div class="pin-list-item">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="product-list">
                <div>
                    <table class="table table-bordered table-hover" style="width:100%; height:auto;">
                        <thead class="thead-dark">
                            <tr>
                                <th><i style="color:#ffffff" class="fal fa-search"></i> Name</th>
                                <th> Base Price</th>
                                <th> Sales Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($artisan->product()->get() as $artisanproduct)
                            <tr>
                                <td>
                                    <a href="">
                                        {{$artisanproduct->product_name}}
                                    </a>
                                </td>
                                <td>{{$artisanproduct->base_price}}</td>
                                <td>{{$artisanproduct->sales_price}}</td>
                            </tr>
                            @empty
                            <tr style="padding-top:40px;">
                                <td colspan='6'>
                                    <div class="col-12 text-center m--font-brand">
                                        <i class='fa fa-exclamation-triangle fa-fw'></i> @lang('messages.empty_product_msg')
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
