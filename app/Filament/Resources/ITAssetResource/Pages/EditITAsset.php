<?php

namespace App\Filament\Resources\ITAssetResource\Pages;

use App\Filament\Resources\ITAssetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditITAsset extends EditRecord
{
    protected static string $resource = ITAssetResource::class;
    protected static ?string $title = 'Edit IT Asset';
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
