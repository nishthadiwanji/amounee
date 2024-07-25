

<a href="#" class="card card-custom bg-white card-stretch gutter-b">
    <!--begin::Body-->
    <div class="card-body">
        <i class="fal fa-lg {{$statistic_block_icon}}"></i>
        <div class="{{$statistic_block_result_class ?? 'm--font-brand'}} font-weight-bolder font-size-h5 mb-2 mt-5">{!! $statistic_block_result !!}</div>
        <div class="font-weight-bold {{$statistic_block_result_class ?? 'm--font-brand'}} font-size-sm">
            {!! $statistic_block_title !!}
        </div>
    </div>
    <!--end::Body-->
</a>