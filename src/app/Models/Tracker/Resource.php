<?php

namespace App\Models\Tracker;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'domain',
        'user_id'
    ];

    public function scopeOwner(Builder $query): void
    {
        $query->where('user_id', Auth::user()->id);
    }
}
