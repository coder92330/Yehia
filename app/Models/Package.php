<?php

namespace App\Models;

use Filament\AvatarProviders\UiAvatarsProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Package extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        "name",
        "description",
        "price",
        "duration",
        "duration_type",
        "users_limit",
        "admin_users_limit",
        "is_active",
        "start_at",
        "end_at",
    ];

    public $translatable = ["name", "description", "duration_type"];

    protected $casts = [
        "price" => "float",
        "is_active" => "boolean",
        "start_at" => "datetime",
        "end_at" => "datetime",
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function agents()
    {
        return $this->hasManyThrough(Agent::class, Company::class);
    }

    public function styles()
    {
        return $this->belongsToMany(Style::class)->withTimestamps();
    }

    public function getDurationNameAttribute()
    {
        $type = match (app()->getLocale()) {
            "ar" => match ($this->duration_type) {
                "يوم"            => $this->duration > 1 ? "أيام" : "يوم",
                "اسبوع", "أسبوع" => $this->duration > 1 ? "أسابيع" : "أسبوع",
                "شهر"            => $this->duration > 1 ? "أشهر" : "شهر",
                "سنة", "سنه"     => $this->duration > 1 ? "سنوات" : "سنة",
            },
            default => $this->duration > 1 ? "{$this->duration_type}s" : $this->duration_type,
        };
        return "$this->duration $type";
    }

    public function getDurationInDaysAttribute()
    {
        if ($this->duration_type === "week") {
            $this->duration *= 7;
        } elseif ($this->duration_type === "month") {
            $this->duration *= 30;
        } elseif ($this->duration_type === "year") {
            $this->duration *= 365;
        }
        return $this->duration;
    }

    public function getDurationInMonthsAttribute()
    {
        if ($this->duration_type === "week") {
            $this->duration *= 4;
        } elseif ($this->duration_type === "day") {
            $this->duration /= 30;
        } elseif ($this->duration_type === "year") {
            $this->duration *= 12;
        }
        return $this->duration;
    }

    public function getDurationInYearsAttribute()
    {
        if ($this->duration_type === "week") {
            $this->duration /= 52;
        } elseif ($this->duration_type === "day") {
            $this->duration /= 365;
        } elseif ($this->duration_type === "month") {
            $this->duration /= 12;
        }
        return $this->duration;
    }

    public function getDurationInWeeksAttribute()
    {
        if ($this->duration_type === "day") {
            $this->duration /= 7;
        } elseif ($this->duration_type === "month") {
            $this->duration *= 4;
        } elseif ($this->duration_type === "year") {
            $this->duration *= 52;
        }
        return $this->duration;
    }

    public function getIconAttribute()
    {
        return (new UiAvatarsProvider)->get($this);
    }

    public function scopeActive($query)
    {
        return $query->where("is_active", 1);
    }

    public function scopeInactive($query)
    {
        return $query->where("is_active", 0);
    }

    public function scopeExpired($query)
    {
        return $query->where("end_at", "<", now());
    }

    public function scopeNotExpired($query)
    {
        return $query->where("end_at", ">=", now());
    }
}
