<?php

namespace App\Filament\Resources\ThresholdResource\Pages;

use App\Filament\Resources\ThresholdResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListThresholds extends ListRecords
{
    protected static string $resource = ThresholdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
