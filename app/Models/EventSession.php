<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class EventSession extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'event_day_id',
        'city_id',
        'start_at',
        'end_at',
        'name',
        'description',
        'lat',
        'lng'
    ];

    public $translatable = ['description'];

    public function day()
    {
        return $this->belongsTo(EventDay::class);
    }

    public function event()
    {
        return $this->hasOneThrough(Event::class, EventDay::class, 'id', 'id', 'event_day_id', 'event_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->city->state->country();
    }

    public function state()
    {
        return $this->city->state();
    }
}
