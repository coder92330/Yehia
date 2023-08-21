<?php

namespace App\Models;

use App\Models\Chat\Message;
use App\Models\Chat\Room;
use App\Models\Tourguide;
use App\Models\Chat\Member;
use Filament\Models\Contracts\HasAvatar;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Filament\Models\Contracts\HasName;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};
use Znck\Eloquent\Traits\BelongsToThrough;

class Agent extends Authenticatable implements HasName, HasMedia, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, HasRoles, BelongsToThrough;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'device_key',
        'country_id',
        "company_id",
        "birthdate",
        "age",
        "years_of_experience",
        "status",
        "is_online",
        "is_active",
        "facebook",
        "twitter",
        "instagram",
        "linkedin",
        "email_verified_at",
        "style_id",
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
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
        return "agent_$this->id";
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('agent_avatar');
    }

    public function getAvatarAttribute()
    {
        return $this->getFirstMedia('agent_avatar')
            ? $this->getFirstMediaUrl('agent_avatar')
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function settings()
    {
        return $this->morphToMany(Setting::class, 'settingable')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phonable');
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouriter');
    }

    public function otp()
    {
        return $this->morphOne(Otp::class, 'otpable');
    }

    public function orders()
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    public function mails()
    {
        return $this->morphMany(Mail::class, 'mailable');
    }

    public function languages(): MorphToMany
    {
        return $this->morphToMany(Language::class, 'languagable')
            ->withPivot('level', 'is_default')
            ->withTimestamps();
    }


    public function members()
    {
        return $this->morphMany(Member::class, 'memberable');
    }

    public function rooms()
    {
        return $this->hasManyThrough(Room::class, Member::class, 'memberable_id', 'id', 'id', 'room_id')
            ->whereMemberableType(self::class);
    }

    public function messages()
    {
        return $this->hasManyThrough(Message::class, Member::class, 'memberable_id', 'sender_id', 'id', 'id')
            ->whereMemberableType(self::class);
    }

    public function scopeAgentMember($query)
    {
        return $query->whereHas('members', fn($q) => $q->where(['memberable_type' => self::class, 'memberable_id' => auth('agent')->id()]));
    }

    public function scopeNotAgentMember($query)
    {
        return $query->whereHas('members', fn($q) => $q->where('memberable_type', '!=', self::class)->where('memberable_id', '!=', auth('agent')->id()));
    }

    public function rates()
    {
        return $this->morphMany(Rate::class, 'ratable');
    }

    public function feedbacks()
    {
        return $this->hasMany(Rate::class, 'agent_id');
    }

    public function scopeFavoriteTourguides($query)
    {
        return $query->whereHas('favourites', fn($q) => $q->where([
            'favouriter_type' => self::class,
            'favouritable_type' => Tourguide::class,
            'favouriter_id' => auth('agent')->id(),
        ]));
    }

    public function package()
    {
        return $this->belongsToThrough(Package::class, Company::class);
    }

    public function style()
    {
        return $this->belongsTo(Style::class);
    }

    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where(['name' => 'super_admin', 'guard_name' => 'agent'])->orWhere(['name' => 'admin', 'guard_name' => 'agent']);
        });
    }

    public function scopeStaffs($query)
    {
        return $query->whereHas('roles', fn($q) => $q->where(['name' => 'user', 'guard_name' => 'agent']));
    }

    public function scopeHasSetting($query, $setting, $value)
    {
        return $query->whereHas('settings', function ($query) use ($setting, $value) {
            $query->where([['key', $setting], ['value', $value]]);
        });
    }
}
