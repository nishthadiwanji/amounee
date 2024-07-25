<div class="card card-custom gutter-b">
    <!--begin::Header-->
    <div class="card-header border-0 py-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label font-weight-bolder text-dark"><i class="fal fa-users text-dark"></i> @lang('labels.latest10_feed')</span>
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
                                <span class="text-dark-75">@lang('labels.post_detail')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.post_type')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.likes')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.comments')</span>
                            </th>
                            <th style="min-width: 175px" class="pl-7">
                                <span class="text-dark-75">@lang('labels.tags')</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feeds as $feed)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$feed->post_detail}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$feed->post_type}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$feed->likes->count()}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        {{$feed->comments->count()}}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                        @foreach($feed->tags as $tag)
                                            {{$tag->tag}}<br>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">
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
                <a href="{{route('feeds.index')}}">@lang('labels.view_all') <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <!--end::Body-->
</div>