<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\EmployeeResource;

class ManageEmployees extends ManageRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Employee')
                ->mutateFormDataUsing(function (array $data): array {
                    // Set the initial division to 'Head Office' if not set
                    $data['employeeId'] = Str::orderedUuid();
                    return $data;
                }),
        ];
    }
}
