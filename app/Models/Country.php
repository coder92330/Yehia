<?php

namespace App\Models;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'countries';

    protected $guarded = [];
    public $translatable = ['name'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function states()
    {
        return $this->hasMany(State::class);
    }

//    public function cities()
//    {
//        return $this->hasManyThrough(City::class, State::class);
//    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
