<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    use HasFactory;

    // permission to save data from ESP32
    protected $fillable = [
        'water_level_cm',
        'rainfall_mm',
        'water_flow_rate',
        'status',
    ];
}
