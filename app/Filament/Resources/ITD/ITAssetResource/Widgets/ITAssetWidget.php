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
            $totalAssets = ITAsset::where('asset_category_id', $category->id)->count();
            $inUseAssets = ITAsset::where('asset_category_id', $category->id)
                ->where('asset_user_id', '!=', null)
                ->count();
            $availableAssets = ITAsset::where('asset_category_id', $category->id)
                ->where('asset_user_id', null)
                ->count();

            // Available stat with category in label
            $stats[] = Stat::make("{$category->name}", $availableAssets)
                ->description("Available")
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'category_id' => [
                            'value' => $category->id,
                        ],
                        'asset_user_id' => [
                            'available' => 'true',
                            'in_use' => 'false',
                        ],
                    ],
                ]));

            // In Use stat with category in label
            $stats[] = Stat::make("{$category->name}", $inUseAssets)
                ->description("In use")
                ->color('warning')
                ->icon('heroicon-o-user')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'category_id' => [
                            'value' => $category->id,
                        ],
                        'asset_user_id' => [
                            'available' => 'false',
                            'in_use' => 'true',
                        ],
                    ],
                ]));

            // Total stat with category in label
            $stats[] = Stat::make("{$category->name}", $totalAssets)
                ->description("Total")
                ->color('primary')
                ->icon('heroicon-o-computer-desktop')
                ->url(ITAssetResource::getUrl('index', [
                    'tableFilters' => [
                        'category_id' => [
                            'value' => $category->id,
                        ],
                    ],
                ]));
        }

        return $stats;
    }
}
