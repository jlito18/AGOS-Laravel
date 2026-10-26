<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    // Tinuturo natin dito yung Blade file na ginawa mo
    protected static string $view = 'filament.pages.auth.login';
}
