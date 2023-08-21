<?php

namespace App\Models;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Znck\Eloquent\Traits\BelongsToThrough;

class City extends Model
{
    use HasFactory, BelongsToThrough, HasTranslations;

    protected $guarded = [];

    public $translatable = ['name'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

//    public function country()
//    {
//        return $this->belongsToThrough(Country::class, State::class);
//    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
