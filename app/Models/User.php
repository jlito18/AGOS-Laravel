<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\SystemLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Models\Contracts\HasName;
use Laravel\Sanctum\HasApiTokens; // 🔴 1. IDAGDAG ITO SA MGA IMPORTS

#[Fillable(['first_name', 'middle_name', 'last_name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasName
{
    // 🔴 2. IDAGDAG ANG HasApiTokens DITO SA LOOB NG USE
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    protected static function booted()
    {
        static::updated(function ($user) {
            $activeUser = \Illuminate\Support\Facades\Auth::check()
                ? \Illuminate\Support\Facades\Auth::user()->first_name . ' ' . \Illuminate\Support\Facades\Auth::user()->last_name
                : 'System';

            SystemLog::create([
                'user_name' => trim($activeUser),
                'action' => 'Updated account credentials for: ' . trim($user->first_name . ' ' . $user->last_name)
            ]);
        });
    }
}
