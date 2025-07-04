<?php

namespace App\Filament\Resources\EmployeePositionResource\Pages;

use App\Filament\Resources\EmployeePositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeePosition extends EditRecord
{
    protected static string $resource = EmployeePositionResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
