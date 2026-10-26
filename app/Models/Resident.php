<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // MAGIC CODE: Kapag dinelete ang Resident sa Admin, buburahin din ang User account niya
    protected static function booted()
    {
        static::deleted(function ($resident) {
            // Hahanapin na natin gamit ang first_name at last_name imbes na 'name' lang
            \App\Models\User::where('first_name', $resident->first_name)
                ->where('last_name', $resident->last_name)
                ->delete();
        });
    }
}
