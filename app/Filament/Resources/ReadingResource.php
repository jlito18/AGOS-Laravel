<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReadingResource\Pages;
use App\Filament\Resources\ReadingResource\RelationManagers;
use App\Models\Reading;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class ReadingResource extends Resource
{
    protected static ?string $model = Reading::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('5s')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Oras ng Update')
                    ->dateTime('M d, Y h:i A')
                    ->timezone('Asia/Manila')
                    ->sortable(),
                TextColumn::make('water_level_cm')
                    ->label('Water Level (cm)')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('rainfall_mm')
                    ->label('Rainfall (mm)')
                    ->sortable(),
                TextColumn::make('water_flow_rate')
                    ->label('Flow Rate')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge() // Para maging magandang badge ang itsura!
                    ->color(fn(string $state): string => match ($state) {
                        'Safe' => 'success',
                        'Warning' => 'warning',
                        'Danger' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc') // Para laging nasa taas ang pinakabago
            ->filters([
                //
            ])
            // ->actions([
            //     Tables\Actions\EditAction::make(),
            // ])
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
            'index' => Pages\ListReadings::route('/'),
            //'create' => Pages\CreateReading::route('/create'),
            //'edit' => Pages\EditReading::route('/{record}/edit'),
        ];
    }
}
