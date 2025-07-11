<?php

namespace App\Filament\Resources\ITD\ITAssetResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ITD\ITAssetUsageHistoryResource;
use Filament\Resources\RelationManagers\RelationManager;

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
                Select::make('asset_location_id')
                    ->label('Location')
                    ->relationship('location', 'name', fn ($query) => $query->orderBy('created_at', 'asc'))
                    ->searchable()
                    ->default(fn () => \App\Models\ITAssetLocation::where('name', 'Head Office')->value('id'))
                    ->createOptionModalHeading('Add New Location')
                    ->createOptionForm([
                        Hidden::make('locationId')
                            ->default(fn () => (string) Str::orderedUuid()),
                        Forms\Components\TextInput::make('name')
                            ->label('Location Name')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('name', ucwords($state)))
                    ])
                    ->preload()
                    ->required(),
                Section::make('Employee Assignment')
                    ->columns(3)
                    ->schema([
                        Select::make('employee_id')
                            ->label('Assign To...')
                            ->relationship('employee', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->initial.' - '.$record->name)
                            ->searchable(['initial', 'name'])
                            ->createOptionModalHeading('Add New Employee')
                            ->createOptionForm([
                                Hidden::make('employeeId')
                                    ->default(fn () => (string) Str::orderedUuid()),
                                Forms\Components\TextInput::make('name')
                                    ->label('Employee Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('initial')
                                    ->label('Initial')
                                    ->required()
                                    ->maxLength(3)
                                    ->unique(\App\Models\Employee::class, 'initial', ignoreRecord: true),
                                Forms\Components\TextInput::make('employee_number')
                                    ->label('Employee Number')
                                    ->required()
                                    ->maxLength(10)
                                    ->unique(\App\Models\Employee::class, 'employee_number', ignoreRecord: true),
                            ])
                            ->preload()
                            ->columnSpanFull(),
                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->createOptionModalHeading('Add New Department')
                            ->createOptionForm([
                                Hidden::make('departmentId')
                                    ->default(fn () => (string) Str::orderedUuid()),
                                Forms\Components\TextInput::make('name')
                                    ->label('Department Name')
                                    ->required()
                                    ->maxLength(255)
                            ])
                            ->preload(),
                        Select::make('division_id')
                            ->label('Division')
                            ->relationship('division', 'name')
                            ->searchable()
                            ->createOptionModalHeading('Add New Division')
                            ->createOptionForm([
                                Hidden::make('divisionId')
                                    ->default(fn () => (string) Str::orderedUuid()),
                                Forms\Components\TextInput::make('name')
                                    ->label('Division Name')
                                    ->required()
                                    ->maxLength(255)
                            ])
                            ->preload(),
                        Select::make('position_id')
                            ->label('Position')
                            ->relationship('position', 'name')
                            ->searchable()
                            ->createOptionModalHeading('Add New Position')
                            ->createOptionForm([
                                Hidden::make('positionId')
                                    ->default(fn () => (string) Str::orderedUuid()),
                                Forms\Components\TextInput::make('name')
                                    ->label('Position Name')
                                    ->required()
                                    ->maxLength(255)
                            ])
                            ->preload()
                    ]),
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
                TextColumn::make('department.name')
                    ->label('Department'),
                TextColumn::make('division.name')
                    ->label('Division'),
                TextColumn::make('position.name')
                    ->label('Position'),
                TextColumn::make('location.name')
                    ->label('Location'),
                TextColumn::make('usage_start_date')
                    ->label('Start Date')
                    ->date(),
                TextColumn::make('usage_end_date')
                    ->label('End Date')
                    ->date(),
                // TextColumn::make('updated_at')
                //     ->label('Last Updated')
                //     ->date(),
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
                        // Update the asset based on the edited usage history
                        if ($record->asset) {
                            // If usage history has an end date, move asset to Head Office and clear user
                            if (!is_null($record->usage_end_date)) {
                                $headOfficeLocation = \App\Models\ITAssetLocation::where('name', 'Head Office')->first();
                                if ($headOfficeLocation) {
                                    $record->asset->asset_location_id = $headOfficeLocation->id;
                                    $record->asset->asset_user_id = null;
                                }
                            } else {
                                // If no end date, update asset to current usage location and user
                                $record->asset->asset_location_id = $record->asset_location_id;
                                $record->asset->asset_user_id = $record->employee_id;
                            }
                            $record->asset->save();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this usage history?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Usage history deleted successfully.')
                    ->requiresConfirmation()
                    ->before(function ($record) {
                        // Before deleting, set asset_user_id to the employee_id of the latest previous usage history (if any)
                        if ($record->asset) {
                            // Find the previous usage history
                            $previousUsage = $record->asset
                                ->usageHistory()
                                ->where('id', '<', $record->id)
                                ->orderByDesc('usage_start_date')
                                ->orderByDesc('id')
                                ->first();

                            // Update asset's user and location to previous usage or null
                            $record->asset->asset_user_id = $previousUsage ? $previousUsage->employee_id : null;
                            $record->asset->asset_location_id = $previousUsage ? $previousUsage->asset_location_id : null;
                            $record->asset->save();

                            // If this record is the latest, set previous usage_end_date to null
                            $latestUsage = $record->asset
                                ->usageHistory()
                                ->where('id', '!=', $record->id)
                                ->orderByDesc('usage_start_date')
                                ->orderByDesc('id')
                                ->first();

                            if ($previousUsage && $latestUsage && $previousUsage->id === $latestUsage->id) {
                                $previousUsage->usage_end_date = null;
                                $previousUsage->save();
                            }
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these usage histories?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Usage histories deleted successfully.')
                        ->requiresConfirmation()
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
