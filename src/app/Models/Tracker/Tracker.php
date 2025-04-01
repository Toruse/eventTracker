<?php

namespace App\Models\Tracker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostname',
        'href',
        'event_id',
        'resource_id',
        'visitor_id',
        'time_event',
        'target',
        'target_id',
        'mouse_x',
        'mouse_y',
        'browser_w',
        'browser_h',
        'event_data'
    ];

    protected $casts = [
        'event_data' => 'json',
    ];

}
