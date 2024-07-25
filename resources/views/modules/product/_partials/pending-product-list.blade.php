<div class="pin-list-item">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="product-list">
                <div>
                    <table class="table table-bordered table-hover" style="width:100%; height:auto;">
                        <thead class="thead-dark">
                            <tr>
                                <th><i style="color:#ffffff" class="fal fa-search"></i> Product Name</th>
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
                                        <b>{{$product->product_name}}</b>
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
                                <td style="vertical-align:middle">
                                    <div class="btn-group">
                                        <a href="{{route('product.edit',[$product->id_token])}}" class="btn btn-outline-warning btn-sm " data-redirect-url="{{route('product.index')}}">
                                            @lang('buttons.edit')
                                        </a>
                                        <button type="button" class="btn btn-outline-info btn-sm pin-approve" data-action="{{route('product.approve',[$product->id_token])}}"><i class="fa fa-check fa-fw"></i> @lang('buttons.approve')</button>
                                        <button type="button" class="btn btn-outline-danger btn-sm pin-reject" data-action="{{route('product.reject',[$product->id_token])}}"><i class="fas fa-times"></i> @lang('buttons.reject')</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr style="padding-top:40px;">
                                <td colspan='6'>
                                    <div class="col-12 text-center m--font-brand">
                                        <i class='fa fa-exclamation-triangle fa-fw'></i> @lang('messages.empty_pending_product_msg')
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
