<?php

namespace App\Filament\Resources\AlertLogResource\Pages;

use App\Filament\Resources\AlertLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlertLogs extends ListRecords
{
    protected static string $resource = AlertLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
