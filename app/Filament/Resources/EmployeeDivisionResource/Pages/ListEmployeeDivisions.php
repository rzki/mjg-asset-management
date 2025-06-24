<?php

namespace App\Filament\Resources\EmployeeDivisionResource\Pages;

use App\Filament\Resources\EmployeeDivisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeDivisions extends ListRecords
{
    protected static string $resource = EmployeeDivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
