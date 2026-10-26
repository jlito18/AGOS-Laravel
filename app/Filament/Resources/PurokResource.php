<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurokResource\Pages;
use App\Filament\Resources\PurokResource\RelationManagers;
use App\Models\Purok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurokResource extends Resource
{
    protected static ?string $model = Purok::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Sa loob ng form() method:
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Pangalan ng Purok / Barangay')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
        ]);
    }

    // Sa loob ng table() method:
    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Purok / Barangay')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPuroks::route('/'),
            'create' => Pages\CreatePurok::route('/create'),
            'edit' => Pages\EditPurok::route('/{record}/edit'),
        ];
    }
}
