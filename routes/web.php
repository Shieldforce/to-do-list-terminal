<?php

use App\Http\Controllers\External\CreateAppController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd(":-(");
});

Route::get('/create-app', [
    CreateAppController::class,
    'createApp'
])
    ->name('createApp');


