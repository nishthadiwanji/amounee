<div class="pin-list-item">
	<div class="row">
	    <div class="col-md-8 col-sm-12">
            <div>
                <div>
                    <h5 style="word-break: break-all;">{{$member->user->name()}}</h5>
                    <h6>{{$member->title}}</h6>
                    <p>
                        <i class="fas fa-phone fa-flip-horizontal fa-fw"></i> <span class="m--font-info">{{!is_null($member->user->phone_number) ? $member->user->full_phone_number() : 'Not Available'}}</span> | <i class="fa fa-at fa-fw"></i> <span class="m--font-info">{{$member->user->email ?? 'Not Available'}}</span></p>
                </div>
            </div>
	    </div>
	    <div class="col-md-4 col-sm-12">
	        <div class="btn-group m-btn-group float-right" role="group">
	            <button type="button" class="btn btn-outline-success btn-sm m-btn m-btn--pill m-btn--square m-btn--icon pin-unban-user" data-action="{{route('team-member.restore',[$member->id_token])}}">
	                <span>
	                    <i class='fa fa-check fa-fw'></i>
	                    <span>@lang('buttons.unban')</span>
	                </span>
	            </button>
	        </div>
	        <br>
	        <br>
	    </div>
	</div>
</div>
