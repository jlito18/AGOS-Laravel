<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SystemLog;

class EvacuationCenter extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($center) {
            SystemLog::create([
                'user_name' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->name : 'System',
                'action' => 'Added a new Evacuation Center'
            ]);
        });

        static::updated(function ($center) {
            SystemLog::create([
                'user_name' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->name : 'System',
                'action' => 'Updated an Evacuation Center record'
            ]);
        });

        static::deleted(function ($center) {
            SystemLog::create([
                'user_name' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->name : 'System',
                'action' => 'Deleted an Evacuation Center'
            ]);
        });
    }
}
