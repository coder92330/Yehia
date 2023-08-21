<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class EventDay extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['description'];

    protected $fillable = [
        'event_id',
        'start_at',
        'end_at',
        'description',
        'type'
    ];

    protected $appends = [
        'start_time',
        'end_time'
    ];

    public function getTypeAttribute()
    {
        return match ($this->attributes['type']) {
            'half' => 'Half Day',
            'full' => 'Full Day',
            default => 'Multiple Days',
        };
    }

    public function getStartTimeAttribute()
    {
        return Carbon::parse($this->attributes['start_at'])->format('H:i');
    }

    public function getEndTimeAttribute()
    {
        return Carbon::parse($this->attributes['end_at'])->format('H:i');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sessions()
    {
        return $this->hasMany(EventSession::class);
    }
}
