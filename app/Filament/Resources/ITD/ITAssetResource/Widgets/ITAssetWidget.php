<?php

namespace App\Filament\Resources\ITD\ITAssetResource\Widgets;

use App\Filament\Resources\ITD\ITAssetResource;
use App\Models\ITAsset;
use App\Models\ITAssetCategory;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ITAssetWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected ?string $heading = 'IT Assets';
    protected function getStats(): array
    {
        $stats = [];
        $categories = ITAssetCategory::orderBy('name')->get();

        foreach ($categories as $category) {
            $totalAssets = ITAsset::where('asset_category_id', $category->name)->count();
            $inUseAssets = ITAsset::where('asset_category_id', $category->name)
                ->where('asset_user_id', '!=', null)
                ->count();
            $availableAssets = ITAsset::where('asset_category_id', $category->name)
                ->where('asset_user_id', null)
                ->count();

            // Available stat
            $stats[] = Stat::make("Available", $availableAssets)
                ->color('success')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'asset_category_id' => [
                            'value' => $category->name,
                        ],
                        'asset_user_id' => [
                            'available' => 'true',
                            'in_use' => 'false',
                        ],
                    ],
                ]));

            // In Use stat
            $stats[] = Stat::make("In Use", $inUseAssets)
                ->color('warning')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'asset_category_id' => [
                            'value' => $category->name,
                        ],
                        'asset_user_id' => [
                            'available' => 'false',
                            'in_use' => 'true',
                        ],
                    ],
                ]));

            // Total stat
            $stats[] = Stat::make("Total", $totalAssets)
                ->color('primary')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'asset_category_id' => [
                            'value' => $category->name,
                        ],
                    ],
                ]));
        }

        return $stats;
    }
}
