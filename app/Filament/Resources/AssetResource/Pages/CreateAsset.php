<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['assetId'] = \Illuminate\Support\Str::orderedUuid();
        $data['pic_id'] = auth()->user()->id; // Automatically set the current user as the PIC
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
