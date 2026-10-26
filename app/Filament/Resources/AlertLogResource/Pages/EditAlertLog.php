<?php

namespace App\Filament\Resources\AlertLogResource\Pages;

use App\Filament\Resources\AlertLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlertLog extends EditRecord
{
    protected static string $resource = AlertLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
