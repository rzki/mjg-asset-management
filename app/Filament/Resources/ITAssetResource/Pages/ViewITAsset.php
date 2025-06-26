<?php

namespace App\Filament\Resources\ITAssetResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ITAssetResource;

class ViewITAsset extends ViewRecord
{
    protected static string $resource = ITAssetResource::class;
    protected static ?string $title = 'View IT Asset';
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('asset_detail')
                ->label('Asset Detail')
                ->url(fn ($record) => route('assets.show', ['assetId' => $record->assetId]))
                ->color('primary'),
            Actions\EditAction::make(),
        ];
    }
}
