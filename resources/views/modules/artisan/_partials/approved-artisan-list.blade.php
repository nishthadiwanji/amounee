<div class="pin-list-item">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="artisan-list">
                <div>
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th><i style="color:#ffffff" class="fal fa-search"></i> Name</th>
                                <th><i style="color:#ffffff" class="fal fa-search"></i> Contact Details</th>
                                <th>Commission</th>
                                <th>Trade Name</th>
                                <th>Craft Practiced</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($artisans as $artisan)
                            <tr>
                                <td>
                                    <a href="{{route('artisan.show',[$artisan->id_token])}}">
                                        {{$artisan->fullName()}}
                                    </a>
                                </td>
                                <td>{{$artisan->email}} <br> {{$artisan->country_code}} {{$artisan->phone_number}}</td>
                                <td>{{$artisan->commission}}</td>
                                <td>{{$artisan->trade_name}}</td>
                                <td>{{$artisan->category->category_name}}</td>
                                <td>
                                    <a href="{{route('artisan.edit',[$artisan->id_token])}}" class="btn btn-outline-warning btn-sm " data-redirect-url="{{route('artisan.index')}}">
                                        @lang('buttons.edit')
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm m-btn m-btn--pill m-btn--square m-btn--icon pin-ban-user" data-action="{{route('artisan.ban-artisan',[$artisan->id_token])}}">
                                        <i class="fa fa-ban fa-fw"></i> @lang('buttons.ban')
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr style="padding-top:40px;">
                                <td colspan='6'>
                                    <div class="text-center m--font-brand">
                                        <i class='fa fa-exclamation-triangle fa-fw'></i> @lang('messages.empty_approved_artisan_msg')
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-4 col-sm-12">
            <div class="btn-group float-right" role="group">
                
            </div>
            <br>
            <br>
        </div> --}}
    </div>
</div>