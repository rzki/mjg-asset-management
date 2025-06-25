<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\ITAsset;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ITAssetResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ITAssetResource\RelationManagers;

class ITAssetResource extends Resource
{
    protected static ?string $model = ITAsset::class;
    protected static ?string $navigationLabel = 'IT Assets';
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
                    ->maxLength(255),
                TextInput::make('asset_code')
                    ->label('Asset Code')
                    ->required()
                    ->maxLength(50),
                DatePicker::make('asset_year_bought')
                    ->label('Asset Year')
                    ->native(false)
                    ->displayFormat('Y')
                    ->format('Y')
                    ->closeOnDateSelection()
                    ->default(now())
                    ->required(),
                TextInput::make('asset_type')
                    ->label('Type')
                    ->required()
                    ->maxLength(100),
                TextInput::make('asset_serial_number')
                    ->label('Serial Number')
                    ->required()
                    ->maxLength(100),
                Select::make('asset_condition')
                    ->label('Condition')
                    ->options([
                        'New' => 'New',
                        'Good' => 'Good',
                        'Fair' => 'Fair',
                        'Poor' => 'Poor',
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
                TextInput::make('asset_user')
                    ->label('Asset User')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('created_at'))
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
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Detail')
                    ->label('Detail')
                    ->url(fn ($record) => route('assets.show', ['assetId' => $record->assetId]))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListITAssets::route('/'),
            'create' => Pages\CreateITAsset::route('/create'),
            'edit' => Pages\EditITAsset::route('/{record}/edit'),
            'view' => Pages\ViewITAsset::route('/{record}'),
        ];
    }
}
