<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function packages()
    {
        return $this->belongsToMany(Package::class)->withTimestamps();
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function scopeDefault($query)
    {
        return $query->where('name', 'violet');
    }

    public static function defaultStyleId()
    {
        return static::default()->first()->id;
    }

    public static function defaultStyle()
    {
        return static::default()->first();
    }
}
