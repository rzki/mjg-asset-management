<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\EmployeeDivision;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeDivisionResource\Pages;
use App\Filament\Resources\EmployeeDivisionResource\RelationManagers;

class EmployeeDivisionResource extends Resource
{
    protected static ?string $model = EmployeeDivision::class;
    protected static ?string $navigationLabel = 'Divisions';
    protected static ?string $navigationIcon = 'heroicon-o-users';
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
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Division Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('initial')
                            ->label('Initial')
                            ->required()
                            ->maxLength(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Division Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('initial')
                    ->label('Initial')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this division?')
                    ->modalDescription('This action cannot be undone.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these divisions?')
                        ->modalDescription('This action cannot be undone.'),
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
            'index' => Pages\ListEmployeeDivisions::route('/'),
            'create' => Pages\CreateEmployeeDivision::route('/create'),
            'edit' => Pages\EditEmployeeDivision::route('/{record}/edit'),
        ];
    }
}
