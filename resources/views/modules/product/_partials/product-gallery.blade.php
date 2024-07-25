<div class="row">
    <div class="col-12">
        <div class="artisan-list">
            <div>
                <table class="table table-bordered table-hover">
                    <col style="width:50%;">
                    <col style="width:50%;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Images</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($productimages = $product->productImages()->get())
                        @forelse($productimages as $productimage)
                        <tr>
                            <td>Image {{ $loop->index + 1 }}</td>
                            <td>
                                <a href="{{$productimage->fileInfo->display_url}}" target="_blank">
                                    See Current File
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr style="padding-top:40px;">
                            <td colspan='2'>
                                <div class="text-center m--font-brand">
                                    <i class='fa fa-exclamation-triangle fa-fw'></i> No files available!
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