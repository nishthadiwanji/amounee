<a href="{{$url}}">
    <div class="row row-no-padding row-col-separator-xl">
        <div class="col-12">
            <div class="pin-widget" style="padding:15px;">
                <div class="display" style="display:flex; width: 100%; height: 100%; margin-bottom: 0px; padding: 10px; box-shadow: 5px 10px 18px #888888;">
                    <div class="number">
                        <h3 class="{{$statistic_block_result_class ?? 'm--font-brand'}}">
                            <span class="{!! $statistic_block_result !!}" data-counter="counterup" data-value="">{!! $statistic_block_result !!}</span>
                        </h3>
                        <small class="{{$statistic_block_result_class ?? 'm--font-brand'}}" style="font-size:11.5px; width:100%; height:100%">{!! $statistic_block_title !!}</small>
                    </div>
                    <div class="icon">
                        <i class="fal fa-fw fa-2x {{$statistic_block_icon}}" style="position:absolute; right:40px; color: #E7B85A;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>