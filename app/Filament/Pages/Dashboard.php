<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    // Ito yung magic code para maging 3 hati ang screen natin!
    public function getColumns(): int | string | array
    {
        return 5;
    }
}
