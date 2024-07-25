<div class="pin-list-item">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="team-member-list">
                <div>
                    <h5 style="word-break: break-all;">
                        <i class="la la-user"></i>
                        <a href="{{route('team-member.show',[$member->id_token])}}">
                            {{$member->user->name()}}
                        </a>
                    </h5>
                    <h6>{{$member->designation}}</h6>
                    <p><i class="fas fa-phone fa-flip-horizontal fa-fw"></i> <span class="m--font-info">{{!is_null($member->user->phone_number) ? $member->user->full_phone_number() : 'Not Available'}}</span> | <i class="fa fa-at fa-fw"></i> <span class="m--font-info">{{$member->user->email ?? 'Not Available'}}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="btn-group float-right" role="group">
                <a href="{{route('team-member.edit',[$member->id_token])}}" class="btn btn-outline-info btn-sm">
                    <i class="fa fa-pencil" style="text-align: center; line-height: 30px;"></i> Edit
                </a>
                <button type="button" class="btn btn-outline-warning btn-sm pin-generate-password" data-url="{{route('attempt-generate-password',[$member->user->id_token])}}" data-name="{{$member->user->full_name()}}"><i class="fal fa-cogs"></i> @lang('buttons.generate_password')</button>
                <button type="button" class="btn btn-outline-danger btn-sm m-btn m-btn--pill m-btn--square m-btn--icon pin-ban-user" data-action="{{route('team-member.destroy',[$member->id_token])}}">
                    <i class="fa fa-ban fa-fw"></i> @lang('buttons.ban')
                </button>
            </div>
            <br>
            <br>
        </div>
    </div>
</div>