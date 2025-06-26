<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ITAssetUsageHistory;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ITAssetUsageHistoryResource\Pages;
use App\Filament\Resources\ITAssetUsageHistoryResource\RelationManagers;

class ITAssetUsageHistoryResource extends Resource
{
    protected static ?string $model = ITAssetUsageHistory::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Asset Management';
    protected static ?string $navigationParentItem = 'IT Assets';
    protected static ?string $navigationLabel = 'IT Asset Usage History';
    protected static ?string $slug = 'it-asset-usage-histories';
    public static function getBreadcrumb(): string
    {
        return 'IT Asset Usage History';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'asset_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->asset_code.' - '.$record->asset_name)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('employee_id')
                    ->label('Assign To...')
                    ->relationship('employee', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->initial.' - '.$record->name)
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('usage_start_date')
                    ->label('Start Date')
                    ->default(now())
                    ->required(),
                DatePicker::make('usage_end_date')
                    ->label('Usage End Date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No IT Asset Usage History Found')
            ->columns([
                TextColumn::make('asset.asset_code')
                    ->label('Asset Code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('asset.asset_name')
                    ->label('Asset Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('employee.name')
                    ->label('Assigned To')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('usage_start_date')
                    ->label('Start Date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('usage_end_date')
                    ->label('End Date')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'asset_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->asset_code.' - '.$record->asset_name),
                SelectFilter::make('employee_id')
                    ->label('Assigned To')
                    ->relationship('employee', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->initial.' - '.$record->name),
            ])
            ->actions([
                Tables\Actions\Action::make('view_asset')
                    ->label('View Asset')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.it-assets.view', ['record' => $record->asset->assetId])),
                    // ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListITAssetUsageHistories::route('/'),
            'create' => Pages\CreateITAssetUsageHistory::route('/create'),
            'edit' => Pages\EditITAssetUsageHistory::route('/{record}/edit'),
        ];
    }
}
