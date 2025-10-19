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
                'accounts' => [
                    'title' => 'Konten',
                    'route' => 'finance.accounts.index',
                    'icon' => 'heroicon-o-credit-card',
                ],
                'transactions' => [
                    'title' => 'Transaktionen',
                    'route' => 'finance.transactions.index',
                    'icon' => 'heroicon-o-arrow-right-left',
                ],
                'budgets' => [
                    'title' => 'Budgets',
                    'route' => 'finance.budgets.index',
                    'icon' => 'heroicon-o-chart-bar',
                ],
                'reports' => [
                    'title' => 'Berichte',
                    'route' => 'finance.reports.index',
                    'icon' => 'heroicon-o-document-text',
                ],
            ],
        ],
    ],
];
