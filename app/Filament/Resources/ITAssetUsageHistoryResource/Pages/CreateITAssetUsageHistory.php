<?php

namespace App\Filament\Resources\ITAssetUsageHistoryResource\Pages;

use Filament\Actions;
use App\Models\ITAsset;
use Illuminate\Support\Str;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ITAssetUsageHistoryResource;

class CreateITAssetUsageHistory extends CreateRecord
{
    protected static string $resource = ITAssetUsageHistoryResource::class;
    protected static ?string $title = 'Create IT Asset Usage History';
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['usageId'] = Str::orderedUuid();
        return $data;
    }
    protected function afterCreate(): void
    {
        $itAssetUsageHistory = $this->record;
        $itAsset = ITAsset::where('id', $itAssetUsageHistory->asset_id)->first();
        if($itAsset){
            ITAsset::where('id', $itAssetUsageHistory->asset_id)->update([
                'asset_user_id' => $itAssetUsageHistory->employee_id,
            ]);
        }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
