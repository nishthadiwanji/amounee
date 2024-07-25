<?php

$dashboard = [
	'Dashboard' => [
		'icon' => 'fas fa-home-lg-alt',
		'url' => 'home'
	]
];
$team_member = [
	'Team Members' => [
		'icon' => 'fal fa-users',
		'sub_menu' => [
			'Add A Member' => [
				'icon' => 'la la-plus',
				'url' => 'team-member.create'
			],
			'Manage Members' => [
				'icon' => 'fas fa-tasks',
				'url' => 'team-member.index'
			]
		]
	]
];
$artisan = [
	'Artisan' => [
		'icon' => 'fal fa-user',
		'sub_menu' => [
			'Add an Artisan' => [
				'icon' => 'la la-plus',
				'url' => 'artisan.create'
			],
			'Manage Artisans' => [
				'icon' => 'fas fa-tasks',
				'url' => 'artisan.index'
			]
		]
	]
];
// $payment = [
// 	'Payment' => [
// 		'icon' => 'far fa-money-bill-wave',
// 		'sub_menu' => [
// 			'Add Payment' => [
// 				'icon' => 'la la-plus',
// 				'url' => 'payment.create'
// 			],
// 			'Manage Payments' => [
// 				'icon' => 'fas fa-tasks',
// 				'url' => 'payment.index'
// 			]
// 		]
// 	]
// ];
$category = [
	'Category' => [
		'icon' => 'fab fa-cotton-bureau',
		'sub_menu' => [
			'Manage Categories' => [
				'icon' => 'fas fa-tasks',
				'url' => 'category.index'
			]
		]
	]
];

$product = [
	'Product' => [
		'icon' => 'fal fa-cubes',
		'sub_menu' => [
			'Add an Product' => [
				'icon' => 'la la-plus',
				 'url' => 'product.create'
			],
			'Manage Products' => [
				'icon' => 'fas fa-tasks',
				'url' => 'product.index'
			]
		]
	]
];
return [
     'admin' => array_merge($dashboard, $team_member, $artisan, $category, $product),
	 'team_member' => array_merge($dashboard, $team_member, $artisan, $category, $product),
];