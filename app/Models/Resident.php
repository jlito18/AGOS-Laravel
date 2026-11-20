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
            \App\Models\User::where('email', $resident->contact_number . '@agos.lgu')
                ->delete();
        });
    }
}
