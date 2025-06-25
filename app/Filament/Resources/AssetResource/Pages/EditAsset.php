<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsset extends EditRecord
{
    protected static string $resource = AssetResource::class;
    protected static ?string $title = 'Edit IT Asset';
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
