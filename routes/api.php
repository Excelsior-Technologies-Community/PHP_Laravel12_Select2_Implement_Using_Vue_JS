<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Tags Routes
Route::get('/tags', [TagController::class, 'index']);
Route::post('/tags', [TagController::class, 'store']);
Route::get('/tags/analytics', [TagController::class, 'analytics']);
Route::get('/tags/orphans', [TagController::class, 'orphanTags']);
Route::post('/tags/cleanup-orphans', [TagController::class, 'cleanupOrphans']);
Route::post('/tags/merge', [TagController::class, 'merge']);

// Products Routes
Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);

// Bulk Product Operations
Route::post('/products/bulk-tag', [ProductController::class, 'bulkTag']);
Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete']);

// Dashboard Statistics
Route::get('/statistics', [ProductController::class, 'statistics']);