<?php

namespace App\Filament\Resources\HotlineResource\Pages;

use App\Filament\Resources\HotlineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHotline extends EditRecord
{
    protected static string $resource = HotlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
