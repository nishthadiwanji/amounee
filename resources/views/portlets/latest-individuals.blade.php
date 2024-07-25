<div class="card card-custom gutter-b">
    <!--begin::Header-->
    <div class="card-header border-0 py-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder text-dark"><i class="fal fa-users text-dark"></i> @lang('labels.latest10_individual')</span>
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
                                <span class="text-dark-75">@lang('labels.profile_photo')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.name')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.email')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.industry')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.job_title')</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($individuals as $individual)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-hover-primary mb-1 font-size-lg">
                                        <img src="{{empty($individual->photo) ? asset('img/no_image.jpg') : $individual->photo->display_thumbnail_url}}" alt="No Image" height="50" width="50">
                                        <!-- <span class="text-muted font-weight-bold d-block">country</span> -->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        <a href="{{route('individuals.show',[$individual->id_token])}}">{{$individual->full_name}}</a>
                                        <!-- <span class="text-muted font-weight-bold d-block">country</span> -->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$individual->user->email}}
                                        <!-- <span class="text-muted font-weight-bold d-block">country</span> -->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$individual->industry->industry ?? 'NA'}}
                                        <!-- <span class="text-muted font-weight-bold d-block">country</span> -->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$individual->jobTitle->job_title ?? 'NA'}}
                                        <!-- <span class="text-muted font-weight-bold d-block">country</span> -->
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
                <a href="{{route('individuals.index')}}">@lang('labels.view_all') <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <!--end::Body-->
</div>