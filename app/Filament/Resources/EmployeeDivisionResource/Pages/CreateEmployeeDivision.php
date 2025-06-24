<?php

namespace App\Filament\Resources\EmployeeDivisionResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EmployeeDivisionResource;

class CreateEmployeeDivision extends CreateRecord
{
    protected static string $resource = EmployeeDivisionResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['divisionId'] = Str::orderedUuid();
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
