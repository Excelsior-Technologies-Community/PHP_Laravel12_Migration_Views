<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', fn() => redirect()->route('user-posts'));

Route::get('/user-posts',        [PostController::class, 'index'])->name('user-posts');
Route::get('/create-post',       [PostController::class, 'create'])->name('create-post');
Route::post('/store-post',       [PostController::class, 'store'])->name('store-post');
Route::get('/edit-post/{id}',    [PostController::class, 'edit'])->name('edit-post');
Route::post('/update-post/{id}', [PostController::class, 'update'])->name('update-post');
Route::post('/delete-post/{id}', [PostController::class, 'destroy'])->name('delete-post');

// Feature 1: Materialized View Refresh
Route::post('/refresh-view',     [PostController::class, 'refreshView'])->name('refresh-view');

// Feature 3: Benchmark Profiler
Route::post('/benchmark',        [PostController::class, 'benchmark'])->name('benchmark');

// Feature 4: Sync Delta
Route::post('/sync-delta',       [PostController::class, 'syncDelta'])->name('sync-delta');

// Feature 5: Replication Status
Route::post('/replication',      [PostController::class, 'replicationStatus'])->name('replication');
