<div class="card card-custom gutter-b">
    <!--begin::Header-->
    <div class="card-header border-0 py-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder text-dark"><i class="fal fa-users text-dark"></i> @lang('labels.latest10_business_profile')</span>
            <!-- <span class="text-muted mt-3 font-weight-bold font-size-sm">Along with count of products</span> -->
        </h3>
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-0 pb-3">
        <div class="tab-content">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table table-head-custom table-head-bg table-borderless table-vertical-center">
                    <thead>
                        <tr class="text-left text-uppercase">
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.owner_name')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.company_name')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.tag_line')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.company_size')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.date_of_foundation')</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($business_profiles as $business_profile)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        <a href="{{route('business-profiles.show',[$business_profile->id_token])}}">{{$business_profile->owner->full_name}}</a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$business_profile->company_name}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$business_profile->tag_line}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$business_profile->company_size}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$business_profile->date_of_foundation}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="text-center m--font-brand" style="padding-top:40px;">
                                    <i class='fa fa-exclamation-triangle fa-fw'></i>
                                    @lang('messages.empty_record')
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!--end::Table-->
        </div>
    </div>
    <div class="card-footer text-center">
        <div class="row">
            <div class="col-12" style="padding-top:10px;padding-bottom:10px;">
                <a href="{{route('business-profiles.index')}}">@lang('labels.view_all') <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <!--end::Body-->
</div>