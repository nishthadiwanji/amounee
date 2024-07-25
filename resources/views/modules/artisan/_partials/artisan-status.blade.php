<h6>Status History:</h6>
<hr>
<div class="row">
    <div class="col-12">
        <div class="artisan-list">
            <div>
                <table class="table table-bordered table-hover">
                    <col style="width:50%;">
                    <col style="width:50%;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($artisanStatuses as $artisanstatus)
                        <tr>
                            <td>{{ $artisanstatus->status }}</td>
                            <td>
                               {{ $artisanstatus->created_at }}
                            </td>
                        </tr>
                        @empty
                        <tr style="padding-top:40px;">
                            <td colspan='2'>
                                <div class="text-center m--font-brand">
                                    <i class='fa fa-exclamation-triangle fa-fw'></i> No status Available!
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