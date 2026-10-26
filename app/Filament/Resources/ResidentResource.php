<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResidentResource\Pages;
use App\Models\Resident;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResidentResource extends Resource
{
    protected static ?string $model = Resident::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Impormasyon ng Residente')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('middle_name')
                            ->label('Middle Name (Optional)')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name (w/ Suffix kung meron)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contact_number')
                            ->label('Contact Number (Hal. 09123456789)')
                            ->tel()
                            ->required()
                            ->maxLength(11),

                        Forms\Components\Select::make('barangay')
                            ->label('Barangay / Purok')
                            // Kukunin niya ang lahat ng 'name' galing sa Purok table
                            ->options(\App\Models\Purok::pluck('name', 'name'))
                            ->searchable() // Para madaling hanapin kung marami
                            ->required(),
                    ])->columns(3), // Hinati sa 3 columns para malinis tingnan
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('middle_name')
                    ->label('M.I.')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contact_number')
                    ->label('Contact Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('barangay')
                    ->label('Barangay')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registration Date')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Pwedeng itago para hindi masyadong masikip
            ])
            ->defaultSort('last_name', 'asc')
            ->filters([
                // 👈 BAGONG DAGDAG NA FILTER
                Tables\Filters\SelectFilter::make('barangay')
                    ->label('I-filter base sa Barangay/Purok')
                    ->options(\App\Models\Purok::pluck('name', 'name')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Deactivate')
                    ->modalHeading('Deactivate Resident')
                    ->modalDescription('Are you sure you want to deactivate this resident? They will not be able to log in to the mobile app.')
                    ->modalSubmitActionLabel('Yes, deactivate'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Deactivate Selected')
                        ->modalHeading('Deactivate Selected Residents')
                        ->modalSubmitActionLabel('Yes, deactivate selected'),
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
            'index' => Pages\ListResidents::route('/'),
            'create' => Pages\CreateResident::route('/create'),
            'edit' => Pages\EditResident::route('/{record}/edit'),
        ];
    }
}
