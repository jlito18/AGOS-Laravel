<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HotlineResource\Pages;
use App\Filament\Resources\HotlineResource\RelationManagers;
use App\Models\Hotline;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HotlineResource extends Resource
{
    protected static ?string $model = Hotline::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'System Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Office Name (e.g., Taytay DRRMO)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number')
                    ->label('Contact Number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Office')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Contact Number'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHotlines::route('/'),
            'create' => Pages\CreateHotline::route('/create'),
            'edit' => Pages\EditHotline::route('/{record}/edit'),
        ];
    }
}
