<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};

class Mail extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'mailable_id',
        'mailable_type',
        'subject',
        'from',
        'to',
        'body',
        'cc',
        'bcc',
        'reply_to',
        'is_mail_sent',
        'status'
    ];

    protected $casts = [
        'is_mail_sent' => 'boolean'
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaCollection('mails');
    }

    public function mailable()
    {
        return $this->morphTo();
    }
}
