<h6><i class="far fa-history"></i>&nbsp;&nbsp;Base Price History</h6>
<div class="row">
    <div class="col-12">
        <div class="artisan-list">
            <div class="scroll" style="overflow-y:auto; height:200px;">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark" style="outline: 1px solid transparent;">
                        <tr>
                            <th style="position:sticky; top:0;">Base Price</th>
                            <th style="position:sticky; top:0;">Updated On</th>
                            <th style="position:sticky; top:0;">Updated By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productPriceHistories as $productPriceHistory)
                        <tr>
                            <td>{{ $productPriceHistory->base_price }}</td>
                            <td>{{ $productPriceHistory->updated_at }}</td>
                            <td>{{ $productPriceHistory->user->first_name }} {{ $productPriceHistory->user->last_name}}</td>
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