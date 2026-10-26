<?php

namespace App\Filament\Resources\ThresholdResource\Pages;

use App\Filament\Resources\ThresholdResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThreshold extends EditRecord
{
    protected static string $resource = ThresholdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
