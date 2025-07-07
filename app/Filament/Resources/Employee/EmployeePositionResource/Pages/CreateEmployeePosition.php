<?php

namespace App\Filament\Resources\Employee\EmployeePositionResource\Pages;

use App\Filament\Resources\Employee\EmployeePositionResource;
use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeePosition extends CreateRecord
{
    protected static string $resource = EmployeePositionResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['positionId'] = Str::orderedUuid();
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
