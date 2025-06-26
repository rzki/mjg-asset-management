<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\ITAsset;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\ITAssetResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ITAssetResource\RelationManagers;
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
                TextInput::make('asset_location')
                    ->label('Location')
                    ->required()
                    ->maxLength(255),
                Select::make('asset_user')
                    ->label('Asset User')
                    ->options(function () {
                        return \App\Models\User::all()->pluck('name', 'id');
                    })
                    ->searchable(),
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
                    ->url(fn ($record) => route('assets.show', ['assetId' => $record->assetId]))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('Asset Details')
                    ->schema([
                        TextEntry::make('asset_name')
                            ->label('Asset Name'),
                        TextEntry::make('asset_code')
                            ->label('Asset Code'),
                        TextEntry::make('asset_serial_number')
                            ->label('Serial Number'),
                        TextEntry::make('asset_year_bought')
                            ->label('Year Bought')
                            ->date('Y'),
                        TextEntry::make('asset_brand')
                            ->label('Brand'),
                        TextEntry::make('asset_model')
                            ->label('Model'),
                        TextEntry::make('category.name')
                            ->label('Category'),
                        TextEntry::make('asset_condition')
                            ->label('Condition'),
                        TextEntry::make('asset_location')
                            ->label('Location'),
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
