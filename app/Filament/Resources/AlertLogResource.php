<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertLogResource\Pages;
use App\Filament\Resources\AlertLogResource\RelationManagers;
use App\Models\AlertLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AlertLogResource extends Resource
{
    protected static ?string $model = AlertLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'System Management'; // Ilalagay natin sa ilalim ng System Management

    // 🔴 MAGIC CODE: Bawal gumawa ng manual na logbook entry ang admin! System lang dapat ang naglalagay!
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Walang form kasi bawal mag-edit
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Petsa at Oras')
                    ->dateTime('M d, Y - h:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('level')
                    ->label('Alert Level')
                    ->badge() // Gagawin nating kulay-kulay para magandang tingnan!
                    ->color(fn(string $state): string => match ($state) {
                        'CRITICAL' => 'danger',
                        'WARNING' => 'warning',
                        'NORMAL' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('message')
                    ->label('System Message')
                    ->wrap(), // Para hindi maputol yung mahahabang mensahe
            ])
            ->defaultSort('created_at', 'desc') // Laging nasa taas yung pinakabagong alarm
            ->actions([]) // Walang Edit at Delete buttons!
            ->bulkActions([]);
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
            'index' => Pages\ListAlertLogs::route('/'),
            'create' => Pages\CreateAlertLog::route('/create'),
            'edit' => Pages\EditAlertLog::route('/{record}/edit'),
        ];
    }
}
