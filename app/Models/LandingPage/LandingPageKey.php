<?php

namespace App\Models\LandingPage;

use App\Models\Navbar;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class LandingPageKey extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    public $translatable = [''];

    protected $fillable = ['key'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaCollection('landing_page_slider');
        $this->addMediaCollection('landing_page_about_us');
        $this->addMediaCollection('landing_page_dvider');
        $this->addMediaCollection('landing_page_subscribe');
        $this->addMediaCollection('landing_page_contact_us');
        $this->addMediaCollection('landing_page_sponsors');
    }

    public function contents()
    {
        return $this->hasMany(LandingPageContent::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function footerMainSection()
    {
        return $this->hasOne(LandingPageContent::class)->where('name', '=','footer_main_section');
    }

    public function getSponsorsImagesAttribute()
    {
        return $this->getMedia('landing_page_sponsors')->map(fn($media) => $media->getFullUrl());
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

    public function navbars()
    {
        return $this->hasMany(Navbar::class)->orderBy('order');
    }
}
