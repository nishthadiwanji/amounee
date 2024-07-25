<div class="pin-list-item">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="artisan-list">
                <div>
                    <table class="table table-bordered table-hover" style="width:100%; height:auto;">
                        <!-- <col style="width:13%;">
                        <col style="width:13%;">
                        <col style="width:13%;">
                        <col style="width:13%;">
                        <col style="width:13%;">
                        <col style="width:35%;"> -->
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
                            <tr class="pin-common-rows">
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
                                    <div class="btn-group">
                                        <a href="{{route('artisan.edit',[$artisan->id_token])}}" class="btn btn-outline-warning btn-sm " data-redirect-url="{{route('artisan.index')}}">
                                            @lang('buttons.edit')
                                        </a>
                                        <button type="button" class="btn btn-outline-info btn-sm approve-artisan-btn" data-toggle="modal" data-target="#approveModal" data-action="{{route('artisan.storeApprovalNote',[$artisan->id_token])}}"><i class="fa fa-check fa-fw"></i> @lang('buttons.approve')</button>
                                        <button type="button" class="btn btn-outline-danger btn-sm reject-artisan-btn" data-toggle="modal" data-target="#rejectModal" data-action="{{route('artisan.storeRejectionNote',[$artisan->id_token])}}"><i class="fas fa-times"></i> @lang('buttons.reject')</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr style="padding-top:40px;">
                                <td colspan='6'>
                                    <div class="col-12 text-center m--font-brand">
                                        <i class='fa fa-exclamation-triangle fa-fw'></i> @lang('messages.empty_pending_artisan_msg')
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

<div id="approveModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <form class="m-form m-form--fit" id="artisanApprovalForm" data-redirect-url="{{route('artisan.index')}}" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Approval Note</h4>
                </div>
                <div class="modal-body">

                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="form-group m-form__group has-feedback">
                                        <textarea name="approval_note" id="approval_note" rows="10" style="width:100%"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn btn-success pin-submit pin-common-submit">
                        @lang('buttons.approve')
                    </button>
                    <button type="button" class="btn btn-default btn-outline-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>

    </div>
</div>

<div id="rejectModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <form class="m-form m-form--fit" id="artisanRejectionForm" data-redirect-url="{{route('artisan.index')}}" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rejection Note</h4>
                </div>
                <div class="modal-body">

                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                        <div class="card-body" style="padding: 1rem 1rem;">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <div class="form-group m-form__group has-feedback">
                                        <textarea name="rejection_note" id="rejection_note" rows="10" style="width:100%"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn btn-success pin-submit pin-common-submit">
                        @lang('buttons.reject')
                    </button>
                    <button type="button" class="btn btn-default btn-outline-warning" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>

    </div>
</div>