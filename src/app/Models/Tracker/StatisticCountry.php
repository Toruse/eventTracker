<?php

namespace App\Models\Tracker;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StatisticCountry extends Model
{
    use HasFactory;

    protected $table = 'statistics_country';

    public function scopeOwner(Builder $query): void
    {
        $query->where('user_id', Auth::user()->id);
    }
}
