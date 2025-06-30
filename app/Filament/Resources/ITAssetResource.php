<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\ITAsset;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ITAssetLocation;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ITAssetResource\Pages;
use App\Filament\Resources\ITAssetResource\RelationManagers\UsageHistoryRelationManager;

class ITAssetResource extends Resource
{
    protected static ?string $model = ITAsset::class;
    protected static ?string $navigationLabel = 'IT Assets';
    protected static ?string $slug = 'it-assets';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Asset Management';
    public static function getBreadcrumb(): string
    {
        return 'IT Assets';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('asset_name')
                    ->label('Asset Name')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                TextInput::make('asset_serial_number')
                    ->label('Serial Number')
                    ->required()
                    ->maxLength(100),
                DatePicker::make('asset_year_bought')
                    ->label('Asset Year')
                    ->native(false)
                    ->displayFormat('Y')
                    ->format('Y')
                    ->closeOnDateSelection()
                    ->default(now())
                    ->required(),
                TextInput::make('asset_brand')
                    ->label('Brand')
                    ->required(),
                TextInput::make('asset_model')
                    ->label('Model')
                    ->required()
                    ->maxLength(100),
                Select::make('asset_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->name}")
                    ->preload()
                    ->searchable()
                    ->required(),
                Select::make('asset_condition')
                    ->label('Condition')
                    ->options([
                        'New' => 'New',
                        'Used' => 'Used',
                        'Defect' => 'Defect',
                        'Disposed' => 'Disposed',
                    ])
                    ->required(),
                Textarea::make('asset_notes')
                    ->label('History/Notes')
                    ->maxLength(500)
                    ->columnSpanFull(),
                // Select::make('asset_location_id')
                //     ->label('Location')
                //     ->relationship('location', 'name', fn ($query) => $query->orderBy('created_at', 'asc'))
                //     ->default(function () {
                //         $headOffice = ITAssetLocation::where('name', 'Head Office')->first();
                //         return $headOffice ? $headOffice->id : null;
                //     })
                //     ->required(),
                // Select::make('asset_user_id')
                //     ->label('Asset User')
                //     ->options(function () {
                //         return Employee::all()->pluck('name', 'id');
                //     })
                //     ->searchable(),
                Textarea::make('asset_remark')
                    ->label('Remark')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('created_at'))
            ->emptyStateHeading('No IT Assets Found')
            ->columns([
                TextColumn::make('asset_name')
                    ->label('Asset Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_code')
                    ->label('Asset Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_year_bought')
                    ->label('Year Bought')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('asset_category_id')
                    ->label('Category')
                    ->getStateUsing(fn ($record) => $record->category ? "{$record->category->name}" : 'N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_condition')
                    ->label('Condition')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_user_id')
                    ->label('User')
                    ->getStateUsing(fn ($record) => $record->employee ? "{$record->employee->name}" : 'N/A')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pic_id')
                    ->label('Created By')
                    ->formatStateUsing(function ($record){
                        $initial = $record->user->employee->initial ?? '';
                        $signature = $initial . ' ' . strtoupper($record->created_at->format('d M Y'));
                        return $signature;
                    })
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->name}")
                    ->preload()
                    ->searchable(),
                SelectFilter::make('asset_condition')
                    ->label('Condition')
                    ->options([
                        'New' => 'New',
                        'Used' => 'Used',
                        'Defect' => 'Defect',
                        'Disposed' => 'Disposed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('Detail')
                    ->label('Detail')
                    ->color('warning')
                    ->url(fn ($record) => route('assets.show', ['assetId' => $record->assetId]))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make()
                    ->icon(null),
                Tables\Actions\EditAction::make()
                    ->icon(null),
                Tables\Actions\DeleteAction::make()
                    ->icon(null)    
                    ->modalHeading('Are you sure you want to delete this asset?')
                    ->modalDescription('This action cannot be undone.')
                    ->successNotificationTitle('Asset deleted successfully.')
                    ->requiresConfirmation()
                    ->after(function ($record) {
                        // After deleting, delete the asset's usage history
                        if ($record->asset) {
                            $record->asset->usageHistory()->delete();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these assets?')
                        ->modalDescription('This action cannot be undone.')
                        ->successNotificationTitle('Assets deleted successfully.')
                        ->requiresConfirmation()
                        ->after(function ($records) {
                            foreach ($records as $record) {
                                if ($record->asset) {
                                    $record->asset->usageHistory()->delete();
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Asset Details')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('asset_name')
                            ->label('Asset Name'),
                        TextEntry::make('asset_code')
                            ->label('Asset Code'),
                        TextEntry::make('asset_serial_number')
                            ->label('Serial Number'),
                        TextEntry::make('asset_year_bought')
                            ->label('Year Bought'),
                        TextEntry::make('asset_brand')
                            ->label('Brand'),
                        TextEntry::make('asset_model')
                            ->label('Model'),
                        TextEntry::make('category.name')
                            ->label('Category'),
                        TextEntry::make('asset_condition')
                            ->label('Condition'),
                        TextEntry::make('location.name')
                            ->label('Location')
                            ->getStateUsing(fn ($record) => $record->location ? $record->location->name : 'N/A'),
                        TextEntry::make('employee.name')
                            ->label('Asset User')
                            ->getStateUsing(fn ($record) => $record->employee ? $record->employee->name : 'N/A'),
                        TextEntry::make('asset_notes')
                            ->label('Notes')
                            ->limit(100),
                        TextEntry::make('asset_remark')
                            ->label('Remark')
                            ->limit(100),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsageHistoryRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListITAssets::route('/'),
            'create' => Pages\CreateITAsset::route('/create'),
            'edit' => Pages\EditITAsset::route('/{record}/edit'),
            'view' => Pages\ViewITAsset::route('/{record}'),
        ];
    }
}
