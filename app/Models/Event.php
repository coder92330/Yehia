<?php

namespace App\Models;

use App\Models\Chat\Message;
use App\Models\Chat\Room;
use Filament\AvatarProviders\UiAvatarsProvider;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Znck\Eloquent\Traits\BelongsToThrough;

class Event extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, BelongsToThrough, HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'agent_id',
        'city_id',
        'name',
        'description',
        'lat',
        'lng',
        'start_at',
        'end_at',
    ];

    protected $appends = [
        'cover',
        'location',
        'full_address',
        'days_type',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaCollection('event_cover_image');
    }

    public function getCoverAttribute()
    {
        return $this->getFirstMedia('event_cover_image')
            ? $this->getFirstMediaUrl('event_cover_image')
            : (new \Filament\AvatarProviders\UiAvatarsProvider)->get($this);
    }

    public function getLocationAttribute()
    {
        return ['lat' => $this->lat, 'lng' => $this->lng];
    }

    public function getFullAddressAttribute()
    {
        return ['lat' => $this->lat, 'lng' => $this->lng];
    }

    public function getDaysTypeAttribute()
    {
        return DB::table('event_days')
            ->where('event_id', $this->id)
            ->select('type')
            ->distinct()
            ->pluck('type')
            ->first();
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function tourguide()
    {
        return $this->hasOneThrough(Tourguide::class, Order::class, 'event_id', 'id', 'id', 'tourguide_id');
    }

    public function company()
    {
        return $this->belongsToThrough(Company::class, Agent::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, City::class, 'id', 'id', 'city_id', 'country_id');
    }

    public function state()
    {
        return $this->city->state();
    }

    public function days()
    {
        return $this->hasMany(EventDay::class);
    }

    public function sessions()
    {
        return $this->hasManyThrough(EventSession::class, EventDay::class, 'event_id', 'event_day_id', 'id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function messages()
    {
        return $this->hasManyThrough(Message::class, Room::class, 'event_id', 'room_id', 'id', 'id');
    }
}
