<div class="pin-list-item">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="product-list">
                <div>
                    <table class="table table-bordered table-hover" style="width:100%; height:auto;">
                        <thead class="thead-dark">
                            <tr>
                                <th><i style="color:#ffffff" class="fal fa-search"></i> Name</th>
                                <th> Artisan</th>
                                <th> Sales Price</th>
                                <th> Commission</th>
                                <th> Stock</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="pin-common-rows">
                                <td>
                                    <a href="{{ route('product.show',[$product->id_token]) }}">
                                        {{$product->product_name}}
                                    </a>
                                    <br>
                                    {{$product->sku}}
                                    <br>
                                    {{$product->category->category_name}} > {{$product->sub_category->category_name}}
                                </td>
                                <td style="vertical-align:middle">
                                    <a href="{{route('artisan.show',[$product->artisan->id_token])}}">
                                        {{$product->artisan->first_name}} {{$product->artisan->last_name}}
                                    </a>
                                </td>
                                <td style="text-align:right;vertical-align:middle;">{{$product->selling_price}}</td>
                                <td style="text-align:right;vertical-align:middle;">{{$product->commision_amount ? $product->commision_amount : "N/A"}}</td>
                                <td style="text-align:center;vertical-align:middle;">
                                    @if($product->stock_status == 'In stock')
                                        <div style="color:green;">{{$product->stock}}</div>
                                    @elseif($product->stock_status == 'Made to order')
                                        <div style="color:orange;">{{$product->stock_status}}</div>
                                    @elseif($product->stock_status == 'Stock Out')
                                        <div style="color:red;">{{$product->stock_status}}</div>
                                    @endif                         
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-success btn-sm m-btn m-btn--pill m-btn--square m-btn--icon pin-unban-user" data-action="{{route('product.unban-product',[$product->id_token])}}">
                                        <span>
                                            <i class='fa fa-check fa-fw'></i>
                                            <span>@lang('buttons.list')</span>
                                        </span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr style="padding-top:40px;">
                                <td colspan='6'>
                                    <div class="col-12 text-center m--font-brand">
                                        <i class='fa fa-exclamation-triangle fa-fw'></i> @lang('messages.empty_delisted_product_msg')
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
