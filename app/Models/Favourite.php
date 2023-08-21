<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;

    protected $fillable = [
        'favouriter_id',
        'favouriter_type',
        'favouritable_id',
        'favouritable_type',
    ];

    public function favouriter()
    {
        return $this->morphTo();
    }

    public function favouritable()
    {
        return $this->morphTo();
    }

    public function scopeMonthly($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }
}
