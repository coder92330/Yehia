<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Service extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $fillable = ['title', 'content', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    public $translatable = ['title', 'content'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('services');
    }

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('services');
    }
}
