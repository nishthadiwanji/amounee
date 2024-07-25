@include('widgets._partials.statistic-block', [
	'url' => route('artisan.index',['status'=>'rejected']),
	'statistic_block_icon' => 'fal fa-user-times m--font-primary',
    'statistic_block_title' => __('widgets.rejected_artisans'),
    'statistic_block_result' => $total_rejected_artisans,
    'statistic_block_result_class' => 'm--font-primary'
])