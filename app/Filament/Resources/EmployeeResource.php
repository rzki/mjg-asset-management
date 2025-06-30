<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'User Management';
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['Super Admin', 'Admin']);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Select User (Optional, select if employee will have login access)')
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select User'),
                    ]),
                Section::make('Employee Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Employee Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('initial')
                            ->label('Initial')
                            ->maxLength(3),
                        Select::make('division_id')
                            ->label('Division')
                            ->relationship('division', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->initial.' '.$record->name)
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Employee Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_number')
                    ->label('Employee Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initial')
                    ->label('Initial')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('division.name')
                    ->label('Division')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position.name')
                    ->label('Position')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Login Access?')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->user ? 'Yes' : 'No';
                    }),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Are you sure you want to delete this employee?')
                    ->modalDescription('This action cannot be undone.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Are you sure you want to delete these employees?')
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
