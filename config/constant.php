<?php


return [
    'storage_disk' => env('STORAGE_DISK', 'local'),
    'default_pagination_records'=> 10,
    // 'LIMIT_ARRAY' => [25, 50, 100, 500],
    'LIMIT_ARRAY' => [10, 20, 50],
    'department_options' => [
        'Service',
        'Sales',
        'Management',
        'HR'
    ],
    'designation_options' => [
        'Executive',
        'Manager',
        'Sr. Manager',
        'Plant Head',
        'Director'
    ],
    'blood_group_options' => [
        'A+',
        'B+',
        'AB+',
        'O+',
        'A-',
        'B-',
        'AB-',
        'O-'
    ],
    'roles'=> [
        'admin' => 'Administrator',
        'team_member' => 'Team Member',
        'artisan' => 'Artisan'
    ],
    'post_type' => [
        '1',
        '2',
        '3'
    ],
    'artisan_statues' => [
        'pending',
        'rejected',
        'approved'
    ],
    'product_statues' => [
        'pending',
        'rejected',
        'approved'
    ],
    'craft_category_options' => [

    ],
    
    'country_options' => [

    ],
    
    'artisan_name_options' => [
        
    ],

    'categories' => [
        'Ajrakh' => [
            'Duppattas',
            'Running Fabric',
            'Sarees',
            'Stoles',
            'Suit Piece'
        ],
        'Applique' => [
            'Bags',
            'Bed Sheets',
            'Curtain',
            'Cushion Covers',
            'Danglers'
        ],
        'Ashavali' => [
            'Sarees'
        ],
        'Bandhani' => [
            'Duppatta',
            'Sarees',
            'Suit piece'
        ],
        'Patola' => [
            'Double Ikat Patola',
            'Patola Duppatta',
            'Sarees',
            'Shawl',
            'Stoles',
            'Suit Piece'
        ]
        ],
    'stock_status' => [
            'In stock',
            'Stock Out',
            'Made to order'
        ],   
    'tax_status' => [
        'Taxable',
        'Shipping Only',
        'None'
    ],
    'tax_class' =>[
        "5" => "Standard",
        "0" => "Zero Rate",
        "2.5" => "2.5 %",
        "5.0" => "5%"
    ]
];
