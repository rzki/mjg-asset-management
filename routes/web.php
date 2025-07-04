<?php

use App\Models\ITAsset;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;
use App\Livewire\Public\AssetDetail;
use App\Livewire\Public\DetailAsset;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ITAssetsController;

Route::get('public/it-assets/details/{assetId}', DetailAsset::class)
    ->name('assets.show');
Route::get('/assets/bulk-export-pdf/export', function () {
    $ids = session('export_asset_ids', []);
    $assets = ITAsset::whereIn('id', $ids)->get();
    $html = view('pdf.assets-list', compact('assets'));
    $filename = 'IT-ASSETS-' . now()->format('Y-m-d') . '.pdf';
    $pdf = Browsershot::html($html)
    ->format('A4')
    ->margins(10, 10, 10, 10)
    ->showBackground()
    ->pdf();
    return response($pdf, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    
})->name('assets.bulk-export-pdf.export');
    