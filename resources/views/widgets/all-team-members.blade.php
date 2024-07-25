@include('widgets._partials.statistic-block', [
	'url' => route('team-member.index'),
	'statistic_block_icon' => 'fa-users m--font-primary',
    'statistic_block_title' => __('widgets.team_members'),
    'statistic_block_result' => $total_team_members,
    'statistic_block_result_class' => 'm--font-primary'
])