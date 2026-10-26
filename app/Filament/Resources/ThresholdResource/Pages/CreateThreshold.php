<?php

namespace App\Filament\Resources\ThresholdResource\Pages;

use App\Filament\Resources\ThresholdResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateThreshold extends CreateRecord
{
    protected static string $resource = ThresholdResource::class;

    // ITO YUNG MAGIC CODE PARA TANGGALIN YUNG BUTTON:
    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    // Bonus: Pagkatapos mag-create, ibabalik ka agad niya sa table list
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
