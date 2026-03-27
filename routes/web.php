<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/user-posts', [PostController::class, 'index'])->name('user-posts');
Route::get('/create-post', [PostController::class, 'create']);
Route::post('/store-post', [PostController::class, 'store']);