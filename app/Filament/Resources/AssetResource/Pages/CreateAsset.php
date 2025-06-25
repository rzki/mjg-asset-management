<?php

namespace App\Filament\Resources\AssetResource\Pages;

use Filament\Actions;
use Milon\Barcode\DNS2D;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\AssetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;
    protected static ?string $title = 'Create IT Asset';
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['assetId'] = \Illuminate\Support\Str::orderedUuid();
        $data['pic_id'] = auth()->user()->id; // Automatically set the current user as the PIC
        $route = route('assets.show', ['assetId' => $data['assetId']]);
        $qr = new DNS2D();
        $qrCodeImage = base64_decode($qr->getBarcodePNG($route, 'QRCODE,H'));
        $path = 'assets/' . $data['assetId'].'.png';
        $data['barcode'] = $path;
        Storage::disk('public')->put($path, $qrCodeImage);
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
