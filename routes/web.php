<?php

use App\Livewire\Public\AssetDetail;
use App\Livewire\Public\DetailAsset;
use Illuminate\Support\Facades\Route;
use App\Livewire\Public\AssetQrCodeTemplate;

Route::get('public/it-assets/details/{assetId}', DetailAsset::class)
    ->name('assets.show');
Route::get('public/it-assets/qr-code/{assetId}', AssetQrCodeTemplate::class)
->name('assets.qr-code');