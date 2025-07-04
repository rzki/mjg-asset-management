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
            Stat::make('In Use', ITAsset::where('asset_user_id', '!=', null)->count())
            ->url(ITAssetResource::getUrl('index', [
                'tableFilters' => [
                    'asset_user_id' => [
                        'available' => 'false',
                        'in_use' => 'true',
                    ],
                ],
            ])),
            Stat::make('Available', ITAsset::where('asset_user_id', null)->count())
            ->url(ITAssetResource::getUrl('index', [
                'tableFilters' => [
                    'asset_user_id' => [
                        'available' => 'true',
                        'in_use' => 'false',
                    ],
                ],
            ])),
            Stat::make('Total', ITAsset::count())
            ->url(ITAssetResource::getUrl('index')),
        ];
    }
}
