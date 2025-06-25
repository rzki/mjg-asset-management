<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;

Route::get('/assets/{assetId}', [AssetController::class, 'show'])
    ->name('assets.show');