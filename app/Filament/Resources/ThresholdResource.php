<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThresholdResource\Pages;
use App\Filament\Resources\ThresholdResource\RelationManagers;
use App\Models\Threshold;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThresholdResource extends Resource
{
    protected static ?string $model = Threshold::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $navigationLabel = 'Alert Settings'; // Custom label for the sidebar navigation

    // MAGIC LOCK 1: Restrict view access to administrators only
    public static function canViewAny(): bool
    {
        return request()->user() && request()->user()->getAttribute('role') === 'admin';
    }

    // Hide the Create button if a configuration record already exists
    public static function canCreate(): bool
    {
        return \App\Models\Threshold::count() === 0;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Water Level (cm)')
                    ->description('Set the threshold values for water level alerts.')
                    ->schema([
                        Forms\Components\TextInput::make('water_warning_cm')
                            ->label('Warning Level (Yellow Alert)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('water_danger_cm')
                            ->label('Danger Level (Red Alert)')
                            ->numeric()
                            ->required(),
                    ])->columns(2), // Display inputs side-by-side

                Forms\Components\Section::make('Rainfall Intensity (mm)')
                    ->description('Set the threshold values for rainfall alerts.')
                    ->schema([
                        Forms\Components\TextInput::make('rain_warning_mm')
                            ->label('Warning Level (Yellow Alert)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('rain_danger_mm')
                            ->label('Danger Level (Red Alert)')
                            ->numeric()
                            ->required(),
                    ])->columns(2),

                // Hazard Map Settings Section
                Forms\Components\Section::make('Hazard Map Settings')
                    ->description('Configure the main sensor location and danger zone radius.')
                    ->schema([
                        Forms\Components\TextInput::make('sensor_latitude')
                            ->label('Sensor Latitude (e.g., 14.5641)')
                            ->numeric(),
                        Forms\Components\TextInput::make('sensor_longitude')
                            ->label('Sensor Longitude (e.g., 121.1394)')
                            ->numeric(),
                        Forms\Components\TextInput::make('hazard_radius_meters')
                            ->label('Hazard Map Radius (Meters)')
                            ->numeric()
                            ->default(500)
                            ->required()
                            ->helperText('Define the coverage radius of the danger zone around the sensor.'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('water_warning_cm')->label('Water Warning (cm)'),
                Tables\Columns\TextColumn::make('water_danger_cm')->label('Water Danger (cm)'),
                Tables\Columns\TextColumn::make('rain_warning_mm')->label('Rain Warning (mm)'),
                Tables\Columns\TextColumn::make('rain_danger_mm')->label('Rain Danger (mm)'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Removed bulk delete action to prevent accidental deletion of system thresholds
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
            'index' => Pages\ListThresholds::route('/'),
            'create' => Pages\CreateThreshold::route('/create'),
            'edit' => Pages\EditThreshold::route('/{record}/edit'),
        ];
    }
}
