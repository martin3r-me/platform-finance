<?php

use Illuminate\Support\Facades\Route;

Route::get('/', Platform\Finance\Livewire\Dashboard::class)->name('finance.dashboard');
Route::get('/chart-of-accounts', Platform\Finance\Livewire\ChartOfAccounts\Index::class)->name('finance.chart-of-accounts.index');