<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvacuationCenterResource\Pages;
use App\Filament\Resources\EvacuationCenterResource\RelationManagers;
use App\Models\EvacuationCenter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EvacuationCenterResource extends Resource
{
    protected static ?string $model = EvacuationCenter::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Pangalan ng Evacuation Center (Hal. San Juan Covered Court)')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('barangay')
                    ->label('Barangay')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('capacity')
                    ->label('Capacity')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Available' => 'Available',
                        'Full' => 'Full',
                        'Under Maintenance' => 'Under Maintenance',
                    ])
                    ->default('Available')
                    ->required(),
                    
                Forms\Components\TextInput::make('latitude')
                    ->label('Latitude (Hal. 14.5641)')
                    ->numeric(),

                Forms\Components\TextInput::make('longitude')
                    ->label('Longitude (Hal. 121.1394)')
                    ->numeric(),
            ]);
            
    }

    /// MAGIC LOCK 2: Admin lang ang pwedeng mag-Add
    public static function canCreate(): bool
    {
        return request()->user() && request()->user()->getAttribute('role') === 'admin';
    }

    // MAGIC LOCK 3: Admin lang ang pwedeng mag-Edit
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return request()->user() && request()->user()->getAttribute('role') === 'admin';
    }

    // MAGIC LOCK 4: Admin lang ang pwedeng mag-Delete
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return request()->user() && request()->user()->getAttribute('role') === 'admin';
    }

    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pangalan')
                    ->searchable() // Pwedeng i-search!
                    ->sortable(),

                Tables\Columns\TextColumn::make('barangay')
                    ->label('Barangay')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Capacity')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge() // Para maging makulay na badge (pill) yung itsura
                    ->color(fn(string $state): string => match ($state) {
                        'Available' => 'success', // Kulay Green
                        'Full' => 'danger',       // Kulay Red
                        'Under Maintenance' => 'warning', // Kulay Yellow
                        default => 'gray',
                    }),
            ])
            ->filters([
                // Pwede tayong magdagdag ng filter dito next time
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvacuationCenters::route('/'),
            'create' => Pages\CreateEvacuationCenter::route('/create'),
            'edit' => Pages\EditEvacuationCenter::route('/{record}/edit'),
        ];
    }
}
