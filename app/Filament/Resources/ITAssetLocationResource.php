<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\ITAssetLocation;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ITAssetLocationResource\Pages;
use App\Filament\Resources\ITAssetLocationResource\RelationManagers;

class ITAssetLocationResource extends Resource
{
    protected static ?string $model = ITAssetLocation::class;
    protected static ?string $slug = 'it-asset-locations';
    protected static ?string $navigationGroup = 'Asset Management';
    protected static ?string $navigationLabel = 'Locations';
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $parentNavigation = 'Asset Management';
    protected static ?string $navigationParentItem = 'IT Assets';
    protected static ?string $modelLabel = 'IT Asset Location';
    protected static ?string $pluralModelLabel = 'IT Asset Locations';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Hidden::make('locationId')
                    ->default(fn () => Str::orderedUuid()->toString()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('No IT Asset Locations Found')
            ->columns([
                TextColumn::make('name')
                    ->label('Location Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageITAssetLocations::route('/'),
        ];
    }
}
