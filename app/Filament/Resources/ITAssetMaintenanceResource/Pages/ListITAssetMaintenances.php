<?php

namespace App\Filament\Resources\ITAssetMaintenanceResource\Pages;

use App\Filament\Resources\ITAssetMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListITAssetMaintenances extends ListRecords
{
    protected static string $resource = ITAssetMaintenanceResource::class;

    protected static ?string $title = 'IT Asset Maintenance Log';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Maintenance Log'),
        ];
    }
}
