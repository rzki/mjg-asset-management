<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ITAssetUsageHistory;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ITAssetUsageHistoryResource\Pages;
use App\Filament\Resources\ITAssetUsageHistoryResource\RelationManagers;

class ITAssetUsageHistoryResource extends Resource
{
    protected static ?string $model = ITAssetUsageHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'asset_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->asset_code.' - '.$record->asset_name)
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('employee_id')
                    ->label('Assign To...')
                    ->relationship('employee', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->initial.' - '.$record->name)
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('usage_start_date')
                    ->label('Start Date')
                    ->required(),
                Forms\Components\DatePicker::make('usage_end_date')
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
                    ->date()
                    ->sortable(),
                TextColumn::make('usage_end_date')
                    ->label('End Date')
                    ->date()
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
