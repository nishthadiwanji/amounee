@include('widgets._partials.statistic-block', [
	'url' => route('artisan.index',['status'=>'approved']),
	'statistic_block_icon' => 'fal fa-user-check m--font-primary',
    'statistic_block_title' => __('widgets.approved_artisans'),
    'statistic_block_result' => $total_approved_artisans,
    'statistic_block_result_class' => 'm--font-primary'
])