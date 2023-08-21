<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'ratable_id',
        'ratable_type',
        'tourguide_id',
        'agent_id',
        'rate',
        'comment',
    ];

    public function ratable()
    {
        return $this->morphTo();
    }

    public function tourguide()
    {
        return $this->belongsTo(Tourguide::class);
    }

    public function getRateAttribute($value)
    {
        return $value / 10;
    }

    public function setRateAttribute($value)
    {
        $this->attributes['rate'] = $value * 10;
    }

    public function getCommentAttribute($value)
    {
        return $value ?? 'No comment';
    }

    public function setCommentAttribute($value)
    {
        $this->attributes['comment'] = $value ?? 'No comment';
    }
}
