<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Threshold extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::updated(function ($threshold) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            SystemLog::create([
                'user_name' => $user ? $user->getFilamentName() : 'System',
                'action' => 'Updated Alert Settings (Threshold or Hazard Radius)',
            ]);
        });
    }
}
