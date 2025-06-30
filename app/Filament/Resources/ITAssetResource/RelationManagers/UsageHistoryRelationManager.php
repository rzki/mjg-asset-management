<?php

namespace App\Filament\Resources\ITAssetResource\RelationManagers;

use App\Filament\Resources\ITAssetUsageHistoryResource;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Str;

class UsageHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'usageHistory';
    public function isReadOnly(): bool
    {
        return false;
    }
    protected static bool $isLazy = true;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('asset', 'asset_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->asset_code.' - '.$record->asset_name)
                    ->default(fn ($context) => $this->getOwnerRecord()->id)
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
                Select::make('asset_location_id')
                    ->label('Location')
                    ->relationship('location', 'name', fn ($query) => $query->orderBy('created_at', 'asc'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('usage_start_date')
                    ->label('Start Date')
                    ->default(now())
                    ->required(),
                DatePicker::make('usage_end_date')
                    ->label('End Date'),
                Hidden::make('usageId')
                    ->default(fn () => (string) Str::orderedUuid()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No IT Asset Usage History Found')
            ->emptyStateDescription('')
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Assigned To'),
                TextColumn::make('location.name')
                    ->label('Location'),
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
                    ->after(function ($record) {
                        // Update the asset's asset_user_id to the employee_id of the new usage history
                        if ($record->asset) {
                            $record->asset->asset_location_id = $record->asset_location_id;
                            $record->asset->asset_user_id = $record->employee_id;
                            $record->asset->save();
                        }
                        $previousUsage = $record->asset
                            ->usageHistory()
                            ->where('id', '<', $record->id)
                            ->orderByDesc('usage_start_date')
                            ->orderByDesc('id')
                            ->first();

                        if ($previousUsage && is_null($previousUsage->usage_end_date)) {
                            $previousUsage->asset_location_id = $record->asset_location_id;
                            $previousUsage->usage_end_date = $record->usage_start_date;
                            $previousUsage->save();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Usage History')
                    ->successNotificationTitle('Usage History Updated Successfully')
                    ->after(function ($record) {
                        // After editing, update the asset's asset_user_id to the employee_id of the latest usage history
                        if ($record->asset) {
                            $record->asset->asset_location_id = $record->asset_location_id;
                            $record->asset->asset_user_id = $record->employee_id;
                            $record->asset->save();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this usage history?')
                    ->modalDescription('This action cannot be undone.')
                    ->before(function ($record) {
                        // Before deleting, set asset_user_id to the employee_id of the latest previous usage history (if any)
                        if ($record->asset) {
                            $previousUsage = $record->asset
                                ->usageHistory()
                                ->where('id', '<', $record->id)
                                ->orderByDesc('usage_end_date')
                                ->orderByDesc('id')
                                ->first();

                            $record->asset->asset_user_id = $previousUsage ? $previousUsage->employee_id : null;
                            $record->asset->asset_location_id = $previousUsage ? $previousUsage->asset_location_id : null;
                            $record->asset->save();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(function ($records) {
                        foreach ($records as $record) {
                            if ($record->asset && is_null($record->usage_end_date)) {
                                $record->asset->asset_user_id = null;
                                $record->asset->asset_location_id = null;
                                $record->asset->save();
                            }
                        }
                    }),
                ]),
            ]);
    }
}
