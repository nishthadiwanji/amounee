<div class="row">
    <div class="col-12">
        <div class="artisan-list">
            <div>
                <table class="table table-bordered table-hover">
                    <col style="width:50%;">
                    <col style="width:50%;">
                    <thead class="thead-dark">
                        <tr>
                            <th>File Type</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($artisanFiles = $artisan->artisanFiles()->whereNotIn('file_type', ['profile_photo'])->get())
                        @forelse($artisanFiles as $artisanFile)
                        <tr>
                            <td>{{ucwords(str_replace('_', ' ', $artisanFile->file_type))}}</td>
                            <td>
                                <a href="{{$artisanFile->fileInfo->display_url}}" target="_blank">
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