<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EvacuationCenter extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($center) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            SystemLog::create([
                'user_name' => $user ? $user->getFilamentName() : 'System',
                'action' => 'Added a new Evacuation Center',
            ]);
        });

        static::updated(function ($center) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            SystemLog::create([
                'user_name' => $user ? $user->getFilamentName() : 'System',
                'action' => 'Updated an Evacuation Center record',
            ]);
        });

        static::deleted(function ($center) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            SystemLog::create([
                'user_name' => $user ? $user->getFilamentName() : 'System',
                'action' => 'Deleted an Evacuation Center',
            ]);
        });
    }
}
