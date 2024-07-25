<div class="pin-list-item">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="product-list">
                <div>
                    <table class="table table-bordered table-hover" style="width:100%; height:auto;">
                        <thead class="thead-dark">
                            <tr style="text-align:center;">
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
                                        <button type="button" class="btn btn-outline-info btn-sm manage_stock_btn" data-toggle="modal" data-target="#stockModal" data-action="{{route('product.updateStock',[$product->id_token])}}" data-redirect-url="{{ route('product.index') }}"><i class="fa fa-check fa-fw"></i>@lang('buttons.manage_stock')</button>
                                        <!-- <button type="button" class="btn btn-outline-info btn-sm manage_stock_btn" data-toggle="modal"  data-target="#stockModal" data-action="{{route('product.updateStock',[$product->id_token])}}"><i class="fa fa-check fa-fw"></i> @lang('buttons.manage_stock')</button> -->
                                        <button type="button" class="btn btn-outline-danger btn-sm m-btn m-btn--pill m-btn--square m-btn--icon pin-ban-user" data-action="{{route('product.ban-product',[$product->id_token])}}">
                                            <i class="fa fa-ban fa-fw"></i> @lang('buttons.delist')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr style="padding-top:40px;">
                                <td colspan='6'>
                                    <div class="col-12 text-center m--font-brand">
                                        <i class='fa fa-exclamation-triangle fa-fw'></i> @lang('messages.empty_approved_product_msg')
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

<div id="stockModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <form class="m-form m-form--fit" id="manageStockForm" action="" data-redirect-url="" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Manage Stock</h4>
                </div>
                <div class="modal-body">

                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group select-form-group ">
                                        <label class="form-control-label" for="stock_status">@lang('labels.stock_status')&nbsp;</label>
                                        <div class="m-select2 m-select2--air">
                                            <select id="stock_status" class="form-control m-select2 pin-stock" name="stock_status" style="width:100%;" data-placeholder="Stock Status">
                                                <option></option>
                                                <option value="In stock">In Stock</option>
                                                <option value="Made to order">Made to Order</option>
                                                <option value="Stock Out">Out of Stock</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group " id="stock_val">
                                        <label class="form-control-label" for="stock">@lang('labels.stock')&nbsp;</label>
                                        <input type="text" name="stock" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn btn-success pin-submit pin-common-submit manage-stock">
                        @lang('buttons.update')
                    </button>
                    <button type="button" class="btn btn-default btn-outline-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>

    </div>
</div>