<?php

namespace App\Filament\Resources\ITAssetLocationResource\Pages;

use App\Filament\Resources\ITAssetLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageITAssetLocations extends ManageRecords
{
    protected static string $resource = ITAssetLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New IT Asset Location'),
        ];
    }
}
