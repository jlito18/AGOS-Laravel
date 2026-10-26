<?php

namespace App\Filament\Resources\EvacuationCenterResource\Pages;

use App\Filament\Resources\EvacuationCenterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvacuationCenter extends EditRecord
{
    protected static string $resource = EvacuationCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
