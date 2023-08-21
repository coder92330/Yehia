<?php

namespace App\Models;

use App\Models\Chat\Member;
use App\Models\Chat\Message;
use App\Models\Chat\Room;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, HasMedia, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'country_id',
        'style_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    protected $appends = ['sender_type'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value)
            ? Hash::make($value)
            : $value;
    }

    public function getSenderTypeAttribute()
    {
        return "user_$this->id";
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = explode('@', $this->email)[0];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('admin_avatar');
    }

    public function getAvatarAttribute()
    {
        return $this->getFirstMedia('admin_avatar')
            ? $this->getFirstMediaUrl('admin_avatar')
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=FFFFFF&background=111827';
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function settings()
    {
        return $this->morphToMany(Setting::class, 'settingable')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phonable');
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouriter');
    }

    public function certificates()
    {
        return $this->morphMany(Certificate::class, 'certificatable');
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

    public function rooms(){
        return $this->hasManyThrough(Room::class, Member::class, 'memberable_id', 'id', 'id', 'room_id')
            ->whereMemberableType(self::class);
    }

    public function messages()
    {
        return $this->hasManyThrough(Message::class, Member::class, 'memberable_id', 'sender_id', 'id', 'id')
            ->whereMemberableType(self::class);
    }

    public function scopeFavoriteTourguides($query)
    {
        return $query->whereHas('favourites', fn($q) => $q->where([
            'favouriter_type'   => self::class,
            'favouritable_type' => Tourguide::class,
            'favouriter_id'     => $this->id,
        ]));
    }

    public function style()
    {
        return $this->belongsTo(Style::class);
    }
}
