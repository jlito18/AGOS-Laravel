<?php

namespace App\Filament\Resources\ResidentResource\Pages;

use App\Filament\Resources\ResidentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateResident extends CreateRecord
{
    protected static string $resource = ResidentResource::class;

    // MAGIC CODE: Pagkatapos ma-save sa 'residents' table, gagawan din natin siya ng account sa 'users' table!
    protected function afterCreate(): void
    {
        $resident = $this->record;

        User::create([
            'first_name' => $resident->first_name,
            'middle_name' => $resident->middle_name,
            'last_name' => $resident->last_name,
            'email' => $resident->contact_number . '@agos.lgu', // Yung email hack natin
            'password' => Hash::make('Agos1234'), // Default password para sa walk-in
            'role' => 'resident',
        ]);
    }

    // Para bumalik sa listahan pagkatapos mag-create
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
