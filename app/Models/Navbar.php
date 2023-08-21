<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Navbar extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['title'];

    protected $fillable = [
        'landing_page_key_id',
        'title',
        'url',
        'parent_id',
        'order',
        'is_active',
    ];

    public function subNavbars()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id')->orderBy('order', 'asc');
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getUrlAttribute()
    {
        if (filter_var($this->attributes['url'], FILTER_VALIDATE_URL)) {
            return $this->attributes['url'];
        }

        if (!str_starts_with($this->attributes['url'], config('app.url'))) {
            if (str_starts_with($this->attributes['url'], '/')) {
                return url($this->attributes['url']);
            }

            return url("/{$this->attributes['url']}");
        }

        return $this->attributes['url'];
    }
}
