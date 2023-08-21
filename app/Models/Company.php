<?php

namespace App\Models;

use Filament\AvatarProviders\UiAvatarsProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Znck\Eloquent\Traits\BelongsToThrough;

class Company extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, BelongsToThrough, HasTranslations;

    public $translatable = ['name', 'address', 'specialties', 'description'];

    protected $fillable = [
        'city_id',
        'package_id',
        'name',
        'email',
        'website',
        'address',
        'specialties',
        'description',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
    ];

    protected $appends = [
        'logo',
        'cover',
        'phone'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('companies_logo');
        $this->addMediaCollection('companies_cover');
    }

    public function getLogoAttribute()
    {
        return $this->getFirstMedia('companies_logo')
            ? $this->getFirstMediaUrl('companies_logo')
            : (new UiAvatarsProvider)->get($this);
    }

    public function getCoverAttribute()
    {
        return $this->getFirstMedia('companies_cover')
            ? $this->getFirstMediaUrl('companies_cover')
            : (new UiAvatarsProvider)->get($this);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsToThrough(Country::class, City::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function events()
    {
        return $this->hasManyThrough(Event::class, Agent::class, 'company_id', 'agent_id', 'id', 'id');
    }

    public function phones()
    {
        return $this->morphMany(Phone::class, 'phonable');
    }

    public function getPhoneAttribute()
    {
        return $this->phones()->exists() ? implode(' - ', $this->phones->pluck('phone')->toArray()) : '-';
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function styles()
    {
        return $this->hasManyThrough(Style::class, PackageStyle::class, 'package_id', 'id', 'package_id', 'style_id');
    }

    public function getIsExceedingPackageAdminsLimitAttribute()
    {
        return $this->agents()->admins()->count() >= $this->package?->admin_users_limit;
    }

    public function getIsExceedingPackageUsersLimitAttribute()
    {
        return $this->agents()->staffs()->count() >= $this->package?->users_limit;
    }

    public function scopeNotExceedingPackageAdminsLimit($query)
    {
        return $query->withCount(['agents' => fn($query) => $query->admins()])->whereRelation('package', 'admin_users_limit', '>', 'agents_count');
    }

    public function scopeNotExceedingPackageUsersLimit($query)
    {
        return $query->withCount(['agents' => fn($query) => $query->staffs()])->whereRelation('package', 'users_limit', '>', 'agents_count');
    }
}
