<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\RoomManagement;
use App\Livewire\InventoryManagement;
use App\Livewire\Dashboard;

Route::view('/', 'welcome');

Route::get('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/rooms', RoomManagement::class)->name('rooms.index');
Route::get('/inventories', InventoryManagement::class)->name('inventories.index');

require __DIR__ . '/auth.php';
