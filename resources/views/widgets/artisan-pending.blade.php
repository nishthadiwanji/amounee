@include('widgets._partials.statistic-block', [
	'url' => route('artisan.index'),
	'statistic_block_icon' => 'fal fa-user-clock m--font-primary',
    'statistic_block_title' => __('widgets.pending_artisans'),
    'statistic_block_result' => $total_pending_artisans,
    'statistic_block_result_class' => 'm--font-primary'
])