<?php

namespace App\Filament\Resources\ITAssetUsageHistoryResource\Pages;

use App\Filament\Resources\ITAssetUsageHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditITAssetUsageHistory extends EditRecord
{
    protected static string $resource = ITAssetUsageHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
