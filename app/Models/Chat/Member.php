<?php

namespace App\Models\Chat;

use App\Models\Agent;
use App\Models\Chat\Room;
use App\Models\Tourguide;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function memberable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->morphTo('memberable');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeWhereNotMe($query, $guard)
    {
        return $query->where('memberable_id', '!=', auth($guard)->id())
            ->where('memberable_type', auth($guard)->user()->getMorphClass());
    }

    public function scopeWhereMe($query, $guard)
    {
        return $query->where([
            'memberable_id' => auth($guard)->id(),
            'memberable_type' => auth($guard)->user()->getMorphClass()
        ]);
    }

    public function scopeWhereTourguide($query)
    {
        return $query->where('memberable_type', Tourguide::class);
    }

    public function scopeWhereNotTourguide($query)
    {
        return $query->where('memberable_type', '!=', Tourguide::class);
    }

    public function scopeWhereUser($query)
    {
        return $query->where('memberable_type', User::class);
    }

    public function scopeWhereAgent($query)
    {
        return $query->where('memberable_type', Agent::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id', 'id');
    }

    public function scopeMyMessages($query, $guard, $with = [])
    {
        $q = $query->whereMe($guard)->whereHas('messages', function ($query) use ($guard) {
            $query->where('sender_id', auth($guard)->id());
        });

        return $with ? $q->with($with) : $q;
    }

    public function scopeOtherMessages($query, $guard, $with = [])
    {
        $q = $query->whereMe($guard)->whereHas('messages', function ($query) use ($guard) {
            $query->where('sender_id', '!=', auth($guard)->id());
        });

        return $with ? $q->with($with) : $q;
    }

    public function scopeIsEnabled($query)
    {
        return $query->where('is_enabled', true);
    }
}
