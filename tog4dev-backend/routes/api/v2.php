<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\CategoryController;
use App\Http\Controllers\Api\V2\ItemController;
use App\Http\Controllers\Api\V2\UserController;
use App\Http\Controllers\Api\V2\PaymentController;
use App\Http\Controllers\Api\V2\QuickContributionController;
use App\Http\Controllers\Api\V2\LanguagesController;

// Public language listing (no API key required) - mirrors v1 for partner consumption
Route::prefix('v2')->group(function () {
    Route::get('/languages', [LanguagesController::class, 'index']);
});

Route::prefix('v2')->middleware(['api.key'])->group(function () {
    // Categories routes
    Route::apiResource('categories', CategoryController::class);
    Route::get('/categories/{id}/status', [CategoryController::class, 'getStatus']);

    // Quick Contributions routes
    Route::apiResource('quick-contributions', QuickContributionController::class);
    Route::get('/quick-contributions/{id}/status', [QuickContributionController::class, 'getStatus']);

    // Items routes
    Route::apiResource('items', ItemController::class);
    Route::get('/items/{id}/status', [ItemController::class, 'getStatus']);

    // User routes
    Route::apiResource('users', UserController::class);

    // Payments routes
    Route::apiResource('payments', PaymentController::class);

});
