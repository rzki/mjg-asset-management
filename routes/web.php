<?php

use App\Livewire\Public\AssetDetail;
use App\Livewire\Public\DetailAsset;
use Illuminate\Support\Facades\Route;

Route::get('public/it-assets/details/{assetId}', DetailAsset::class)
    ->name('assets.show');