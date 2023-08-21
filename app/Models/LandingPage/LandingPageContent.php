<?php

namespace App\Models\LandingPage;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class LandingPageContent extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    public $translatable = [
        'name',
        'title',
        'content',
        'button_text',
    ];

    protected $fillable = [
        'landing_page_key_id',
        'name',
        'title',
        'content',
        'url',
        'button_text',
        'button_url',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaCollection('landing_page_slider');
        $this->addMediaCollection('landing_page_about_us');
        $this->addMediaCollection('landing_page_services');
        $this->addMediaCollection('landing_page_dvider');
        $this->addMediaCollection('landing_page_subscribe');
        $this->addMediaCollection('landing_page_contact_us');
    }

    public function key()
    {
        return $this->belongsTo(LandingPageKey::class, 'landing_page_key_id');
    }

    public function getSponsorsAttribute()
    {
        return $this->getMedia('landing_page_sponsors')->map(fn($media) => $media->getFullUrl());
    }

    public function getHeroSliderAttribute()
    {
        return $this->getMedia('landing_page_slider_hero')->map(fn($media) => $media->getFullUrl());
    }

    public function getSliderAttribute()
    {
        return $this->getFirstMediaUrl('landing_page_slider');
    }

    public function getAboutUsAttribute()
    {
        return $this->getMedia('landing_page_about_us')->map(fn($media) => $media->getFullUrl());
    }

    public function getDviderAttribute()
    {
        return $this->getFirstMediaUrl('landing_page_dvider');
    }

    public function getSubscribeAttribute()
    {
        return $this->getFirstMediaUrl('landing_page_subscribe');
    }

    public function getContactUsAttribute()
    {
        return $this->getFirstMediaUrl('landing_page_contact_us');
    }
}
