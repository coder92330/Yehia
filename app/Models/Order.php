<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;

class Order extends Model
{
    use HasFactory, BelongsToThrough;

    protected $fillable = ['orderable_id', 'orderable_type', 'event_id'];

    protected $appends = ['location'];

    public function getLocationAttribute()
    {
        return $this->event->location;
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function agent()
    {
        return $this->belongsToThrough(Agent::class, Event::class);
    }

    public function tourguides()
    {
        return $this->belongsToMany(Tourguide::class, 'order_tourguide', 'order_id', 'tourguide_id')
            ->withPivot('status', 'agent_status')
            ->withTimestamps();
    }

    public function orderable()
    {
        return $this->morphTo();
    }

    public function getTourguideStatusAttribute()
    {
        return auth('tourguide')->check() && $this->tourguides->where('id', auth('tourguide')->id())->count() > 0 ?
            $this->tourguides->where('id', auth('tourguide')->id())->first()->pivot->status
            : null;
    }

    public function getAgentStatusAttribute()
    {
        return auth('tourguide')->check() && $this->tourguides->where('id', auth('tourguide')->id())->count() > 0 ?
            $this->tourguides->where('id', auth('tourguide')->id())->first()->pivot->agent_status
            : null;
    }
}
