<?php

namespace App\Filament\Resources\ITAssetResource\RelationManagers;

use App\Filament\Resources\ITAssetUsageHistoryResource;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class UsageHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'usageHistory';
    public function isReadOnly(): bool
    {
        return false;
    }
    public function form(Form $form): Form
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
                    ->label('End Date')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No IT Asset Usage History Found')
            ->emptyStateDescription('')
            ->columns([
                TextColumn::make('asset.asset_code')
                    ->label('Asset Code'),
                TextColumn::make('asset.asset_name')
                    ->label('Asset Name'),
                TextColumn::make('employee.name')
                    ->label('Assigned To'),
                TextColumn::make('usage_start_date')
                    ->label('Start Date')
                    ->date(),
                TextColumn::make('usage_end_date')
                    ->label('End Date')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Assign Asset')
                    ->modalHeading('Assign Asset')
                    ->successNotificationTitle('Asset Assigned Successfully')
                    ->url(ITAssetUsageHistoryResource::getUrl('create')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
