<?php

return [

    [
        'section' => 'Home',
        'roles' => ['admin', 'cashier', 'customer'],
        'items' => [
            [
                'label' => 'Dashboard',
                'icon'  => 'ti ti-smart-home',
                'route' => 'dashboard',
                'roles' => ['admin', 'cashier', 'customer'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | CASHIER MENU
    |--------------------------------------------------------------------------
    */
    [
        'section' => 'Cashier',
        'roles' => ['cashier'],
        'items' => [
            
            [
                'label' => 'Orders',
                'icon'  => 'ti ti-clipboard-list',
                'route' => 'cashier.order.index',
                'roles' => ['cashier'],
            ],
            [
                'label' => 'Manual Order',
                'icon'  => 'ti ti-pencil-plus',
                'route' => 'cashier.order.create',
                'roles' => ['cashier'],
            ],
            [
                'label' => 'Menus',
                'icon'  => 'ti ti-soup',
                'route' => 'cashier.menu.index',
                'roles' => ['cashier'],
            ],
            // [
            //     'label' => 'Payments',
            //     'icon'  => 'ti ti-credit-card',
            //     'route' => 'payment.index',
            //     'roles' => ['cashier'],
            // ],
            [
                'label' => 'Transactions',
                'icon'  => 'ti ti-report-money',
                'route' => 'cashier.transaction.index',
                'roles' => ['cashier'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ADMIN MENU
    |--------------------------------------------------------------------------
    */
    [
        'section' => 'Data Management',
        'roles' => ['admin'],
        'items' => [
            [
                'label' => 'Menu',
                'icon'  => 'ti ti-tools-kitchen',
                'route' => 'admin.menu.index',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Category',
                'icon'  => 'ti ti-category-2',
                'route' => 'admin.category.index',
                'roles' => ['admin'],
            ],
            [
                'label' => 'Table',
                'icon'  => 'ti ti-components',
                'route' => 'admin.table.index',
                'roles' => ['admin'],
            ],
            [
                'label' => 'User',
                'icon'  => 'ti ti-user',
                'route' => 'admin.user.index',
                'roles' => ['admin'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ADMIN REPORT & MONITORING
    |--------------------------------------------------------------------------
    */
    [
        'section' => 'Report & Monitoring',
        'roles' => ['admin'],
        'items' => [
            [
                'label' => 'Orders',
                'icon'  => 'ti ti-clipboard-list',
                'route' => 'admin.order.index',
                'roles' => ['admin'],
            ],
        ],
    ],

];
