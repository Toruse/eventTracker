<?php

namespace App\Models\Tracker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'locale',
        'ip_address',
        'browser_type',
        'browser_family',
        'browser_version',
        'browser_engine',
        'platform_family',
        'platform_version',
        'device_family',
        'device_model'
    ];

}
