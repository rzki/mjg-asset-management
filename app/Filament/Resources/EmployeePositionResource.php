<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\EmployeePosition;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeePositionResource\Pages;
use App\Filament\Resources\EmployeePositionResource\RelationManagers;

class EmployeePositionResource extends Resource
{
    protected static ?string $model = EmployeePosition::class;
    protected static ?string $navigationLabel = 'Positions';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationParentItem = 'Employees';
    protected static ?string $navigationGroup = 'User Management';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['Super Admin', 'Admin']);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Position Name')
                    ->required()
                    ->maxLength(255),
                Select::make('division_id')
                    ->label('Division')
                    ->relationship('division', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Select Division'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Position Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('division.name')
                    ->label('Division')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
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
            'index' => Pages\ListEmployeePositions::route('/'),
            'create' => Pages\CreateEmployeePosition::route('/create'),
            'edit' => Pages\EditEmployeePosition::route('/{record}/edit'),
        ];
    }
}
