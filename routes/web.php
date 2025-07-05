<?php

use App\Models\ITAsset;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Livewire\Public\AssetDetail;
use App\Livewire\Public\DetailAsset;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ITAssetsController;

Route::get('public/it-assets/details/{assetId}', DetailAsset::class)
    ->name('assets.show');
Route::get('/assets/bulk-export-pdf/preview', function () {
    $ids = session('export_asset_ids', []);
    $assets = ITAsset::whereIn('id', $ids)->get();
    return view('pdf.assets-list', compact('assets'));
})->name('assets.bulk-export-pdf.preview');
    