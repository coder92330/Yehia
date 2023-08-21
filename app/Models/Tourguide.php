<?php

namespace App\Models;

use App\Models\Chat\Message;
use App\Models\Chat\Room;
use App\Models\Chat\Member;
use Filament\Models\Contracts\HasAvatar;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Hash;
use Filament\Models\Contracts\HasName;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Translatable\HasTranslations;
use Znck\Eloquent\Traits\BelongsToThrough;

class Tourguide extends Authenticatable implements HasName, HasMedia, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, BelongsToThrough, HasTranslations;

    public $translatable = ['bio'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'device_key',
        'country_id',
        'city_id',
        "birthdate",
        "age",
        "education",
        "years_of_experience",
        "status",
        "is_online",
        "last_active",
        "facebook",
        "twitter",
        "instagram",
        "linkedin",
        "gender",
        "email_verified_at",
        "bio",
        "style_id",
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        "email_verified_at" => "datetime",
        "birthdate" => "date",
        "is_active" => "boolean",
        "is_online" => "boolean",
    ];

    protected $appends = ['sender_type'];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar;
    }

    public function getSenderTypeAttribute()
    {
        return "tourguide_$this->id";
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('tourguide_avatar')
            ->singleFile();
    }

    public function getAvatarAttribute()
    {
        return $this->getFirstMedia('tourguide_avatar')
            ? $this->getFirstMediaUrl('tourguide_avatar')
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=FFFFFF&background=111827';
    }

    public function getFilamentName(): string
    {
        return "$this->first_name $this->last_name";
    }

    public function getFullNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    public function getAddressAttribute()
    {
        return "{$this->country?->name}, {$this->city?->name}";
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function getRateAttribute()
    {
        return $this->rates->isNotEmpty() ? $this->rates->avg('rate') : 0;
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsToThrough(Country::class, City::class);
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouritable');
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phonable');
    }

    public function otp()
    {
        return $this->morphOne(Otp::class, 'otpable');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_tourguide', 'tourguide_id', 'order_id')
            ->withPivot('status', 'agent_status')
            ->withTimestamps();
    }

    public function work_experiences()
    {
        return $this->morphMany(WorkExperience::class, 'workable');
    }

    public function languages()
    {
        return $this->morphToMany(Language::class, 'languagable')
            ->withPivot('level', 'is_default')
            ->withTimestamps();
    }

    public function skills()
    {
        return $this->morphToMany(Skill::class, 'skillable')
            ->withPivot('level')
            ->withTimestamps();
    }

    public function certificates()
    {
        return $this->morphMany(Certificate::class, 'certifiable');
    }


    public function members()
    {
        return $this->morphMany(Member::class, 'memberable');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'members', 'memberable_id', 'room_id')
            ->whereMemberableType(self::class);
    }

    public function messages()
    {
        return $this->hasManyThrough(Message::class, Member::class, 'memberable_id', 'sender_id', 'id', 'id')
            ->whereMemberableType(self::class);
    }

    public function settings()
    {
        return $this->morphToMany(Setting::class, 'settingable')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function rates()
    {
        return $this->morphMany(Rate::class, 'ratable');
    }

    // Avg Rates
    public function getAvgRatesAttribute()
    {
        return $this->rates->isNotEmpty() ? $this->rates->avg('rate') : 0;
    }

    // Count Rates
    public function getRatesCountAttribute()
    {
        return $this->rates->count();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public static function available($event_id)
    {
        $event = Event::find($event_id);
        return Tourguide::whereHas('appointments', fn($q) => $q->where([['start_at', '<=', $event->start_at], ['end_at', '>=', $event->end_at]]));
    }

    public function availableForBooking($event_id): bool
    {
        $event = Event::find($event_id);
        return $this->appointments()->where([['start_at', '<=', $event->start_at], ['end_at', '>=', $event->end_at]])->doesntExist();
    }

    public function views()
    {
        return $this->morphMany(ProfileView::class, 'viewable');
    }

    public function scopeCountNewTourguidePerMonth($query)
    {
        // Count Tourguide Every Month in Current Year
        return $query->selectRaw('count(id) as count, MONTH(created_at) month')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month', 'ASC');
    }

    public function scopeRecommended($query)
    {
        return $query->whereHas('favourites', function ($q) {
            $q->where([
                ['favouriter_type', User::class],
                ['favouritable_type', self::class],
                ['favouritable_id', $this->id],
            ]);
        });
    }

    public function scopeFavouritedBy($query, $user)
    {
        return $query->whereHas('favourites', function ($q) use ($user) {
            $q->where([
                ['favouriter_type', $user->getMorphClass()],
                ['favouriter_id', $user->id],
                ['favouritable_type', self::class]
            ]);
        });
    }

    public function style()
    {
        return $this->belongsTo(Style::class);
    }

    public function scopeHasSetting($query, $setting, $value)
    {
        return $query->whereHas('settings', function ($query) use ($setting, $value) {
            $query->where([['key', $setting], ['value', $value]]);
        });
    }

    public function canAssignToEvent($event_id)
    {
        if (($event = Event::find($event_id)) && $event->days()->exists()) {
            $type = $event->days()->pluck('type')->unique()->toArray();
            $key = match ($type) {
                ['half'], ['Half Day'] => 'assign_half_day_events',
                ['full'], ['Full Day'] => 'assign_full_day_events',
                ['multi'], ['Multiple Days'] => 'assign_multi_day_events',
                default => null
            };
            return $key ? $this->hasSetting($key, true)->exists() : false;
        }
        return true;
    }

    public function doesntBookedFromAgent($agent_id, $event_id)
    {
        return $this->orders()->where([
            ['orderable_type', Agent::class],
            ['orderable_id', $agent_id],
            ['event_id', $event_id]
        ])->doesntExist();
    }

    public function scopeDoesntRejectOrder($query, $event_id)
    {
        return $query->whereHas('orders', function ($query) use ($event_id) {
            $query
                ->where([['orderable_type', Agent::class], ['event_id', $event_id]])
                ->whereRelation('tourguides', 'status', '!=', 'rejected');
        });
    }
}
