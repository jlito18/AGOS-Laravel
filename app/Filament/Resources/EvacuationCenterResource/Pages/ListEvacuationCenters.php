<?php

namespace App\Filament\Resources\EvacuationCenterResource\Pages;

use App\Filament\Resources\EvacuationCenterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvacuationCenters extends ListRecords
{
    protected static string $resource = EvacuationCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
