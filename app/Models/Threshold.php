<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SystemLog;

class Threshold extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::updated(function ($threshold) {
            SystemLog::create([
                'user_name' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::user()->name : 'System',
                'action' => 'Updated Alert Settings (Threshold or Hazard Radius)'
            ]);
        });
    }
}
