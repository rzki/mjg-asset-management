<?php

namespace App\Filament\Resources\ITAssetResource\Widgets;

use App\Filament\Resources\ITAssetResource;
use App\Models\ITAsset;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ITAssetWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected ?string $heading = 'IT Assets';
    protected function getStats(): array
    {
        return [
            Stat::make('Total Assets', ITAsset::count())
            ->url(ITAssetResource::getUrl('index')),
            Stat::make('Assets in Use', ITAsset::where('asset_user_id', '!=', null)->count()),
            Stat::make('Assets Available', ITAsset::where('asset_user_id', null)->count()),
        ];
    }
}
