<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('organisation', 'pages::organisation.members')
        ->middleware('can:manage-organisation')
        ->name('organisation.index');
});

require __DIR__.'/settings.php';
