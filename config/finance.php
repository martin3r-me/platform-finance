<?php

return [
    'name' => 'Finance',
    'description' => 'Finance Module',
    'version' => '1.0.0',
    
    'routing' => [
        'prefix' => 'finance',
        'middleware' => ['web', 'auth'],
    ],
    
    'guard' => 'web',
    
    'navigation' => [
        'main' => [
            'finance' => [
                'title' => 'Finance',
                'icon' => 'heroicon-o-currency-dollar',
                'route' => 'finance.dashboard',
            ],
        ],
    ],
    
    'sidebar' => [
        'finance' => [
            'title' => 'Finance',
            'icon' => 'heroicon-o-currency-dollar',
            'items' => [
                'dashboard' => [
                    'title' => 'Dashboard',
                    'route' => 'finance.dashboard',
                    'icon' => 'heroicon-o-home',
                ],
                'chart-of-accounts' => [
                    'title' => 'Kontenplan',
                    'route' => 'finance.chart-of-accounts.index',
                    'icon' => 'heroicon-o-banknotes',
                ],
                'account-types' => [
                    'title' => 'Kontenarten',
                    'route' => 'finance.account-types.index',
                    'icon' => 'heroicon-o-squares-2x2',
                ],
            ],
        ],
    ],
];
