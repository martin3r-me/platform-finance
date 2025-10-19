<?php

use Illuminate\Support\Facades\Route;

Route::get('/', Platform\Finance\Livewire\Dashboard::class)->name('finance.dashboard');