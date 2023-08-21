<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourguideSubmitForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'address',
        'gender',
        'date_of_birth',
        'languages'
    ];

    protected $casts = [
        'languages' => 'json'
    ];

    protected $appends = [
        'language_ids',
    ];

    public function getLanguageIdsAttribute()
    {
        return json_decode($this->attributes['languages']);
    }

    public function getLanguagesAttribute()
    {
        return implode(', ', Language::whereIn('id', json_decode($this->attributes['languages']))->get()->pluck('name')->toArray());
    }
}
