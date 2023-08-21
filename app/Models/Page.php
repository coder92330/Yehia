<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Page extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'style',
        'script',
        'is_active',
        'is_header_active'
    ];

    public $translatable = ['title', 'body', 'style', 'script'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('page_header');
    }

    public function getHeaderAttribute()
    {
        return $this->getFirstMediaUrl('page_header');
    }

    public function getFullUrlAttribute()
    {
        return route('page.show', $this->slug);
    }

    public function getFullMobileUrlAttribute()
    {
        return route('page.show', ['slug' => $this->slug, 'mobile' => true]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
