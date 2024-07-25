<h6><i class="far fa-history"></i>&nbsp;&nbsp;Stock History</h6>
<div class="row">
    <div class="col-12">
        <div class="artisan-list">
            <div class="scroll" style="overflow-y:auto; height:200px;">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark" style="outline: 1px solid transparent;">
                        <tr>
                            <th style="position:sticky; top:0;">Stock Status</th>
                            <th style="position:sticky; top:0;">Stock</th>
                            <th style="position:sticky; top:0;">Updated On</th>
                            <th style="position:sticky; top:0;">Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productStockHistories as $productStockHistory)
                        <tr>
                            <td>
                                @if($productStockHistory->stock_status=='In stock')
                                <span style="color:green">{{ $productStockHistory->stock_status}}</span>
                                @elseif($productStockHistory->stock_status=='Stock Out')
                                <span style="color:red">{{ $productStockHistory->stock_status}}</span>
                                @else
                                <span style="color:blue">{{ $productStockHistory->stock_status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($productStockHistory->stock_status=='Stock Out')
                                00
                                @else
                                {{ $productStockHistory->stock ? $productStockHistory->stock : "N/A" }}
                                @endif
                            </td>
                            <td>{{ $productStockHistory->updated_at }}</td>
                            <td>{{ $productStockHistory->user->first_name }} {{ $productStockHistory->user->last_name}}</td>
                        </tr>
                        @empty
                        <tr style="padding-top:40px;">
                            <td colspan='2'>
                                <div class="text-center m--font-brand">
                                    <i class='fa fa-exclamation-triangle fa-fw'></i> No History available!
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