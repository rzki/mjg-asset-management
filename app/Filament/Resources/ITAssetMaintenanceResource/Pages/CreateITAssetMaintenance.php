<?php

namespace App\Filament\Resources\ITAssetMaintenanceResource\Pages;

use Filament\Actions;
use App\Models\Employee;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ITAssetMaintenanceResource;

class CreateITAssetMaintenance extends CreateRecord
{
    protected static string $resource = ITAssetMaintenanceResource::class;
    protected static ?string $title = 'Create IT Asset Maintenance Log';
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['maintenanceId'] = Str::orderedUuid();
        $data['pic_id'] = auth()->user()->id;
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
