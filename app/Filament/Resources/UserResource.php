<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'System Management'; // Para maayos tingnan

    // MAGIC LOCK: Super Admin lang ang pwedeng makapasok sa User Management!
    public static function canViewAny(): bool
    {
        return request()->user() && request()->user()->getAttribute('role') === 'admin';
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\TextInput::make('first_name')
                ->label('First Name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('middle_name')
                ->label('Middle Name'),
            Forms\Components\TextInput::make('last_name')
                ->label('Last Name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(fn(string $context): bool => $context === 'create')
                ->dehydrated(fn($state) => filled($state)),
            Forms\Components\Select::make('role')
                ->label('User Role (Posisyon)')
                ->options([
                    'admin' => 'Super Admin (Lahat ng Access)',
                    'staff' => 'LGU Staff (Monitor Lang)',
                ])
                ->default('staff')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('last_name')
                ->label('Pangalan')
                ->formatStateUsing(fn($record) => $record->last_name . ', ' . trim($record->first_name . ' ' . $record->middle_name))
                ->searchable(['first_name', 'last_name']),
            Tables\Columns\TextColumn::make('email')
                ->label('Email Address')
                ->searchable(),
            Tables\Columns\TextColumn::make('role')
                ->label('Posisyon')
                ->badge() // Lalagyan natin ng kulay para astig!
                ->color(fn(string $state): string => match ($state) {
                    'admin' => 'danger',  // Red kapag Admin
                    'staff' => 'success', // Green kapag Staff
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Petsa Ginawa')
                ->dateTime('M d, Y - h:i A')
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make() // Gamitin natin itong built-in delete action
                    ->label('Deactivate')
                    ->modalHeading('Deactivate User')
                    ->modalDescription('Are you sure you want to deactivate this user? They will lose access to the system.')
                    ->modalSubmitActionLabel('Yes, deactivate'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Deactivate Selected')
                        ->modalHeading('Deactivate Selected Users')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // MAGIC CODE: Itatago nito ang lahat ng mobile app users, para Admin at Staff lang ang nasa listahan
        return parent::getEloquentQuery()->where('role', '!=', 'resident');
    }
}
