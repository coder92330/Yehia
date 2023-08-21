<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class WorkExperience extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'workable_id',
        'workable_type',
        'title',
        'company',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    public $translatable = ['title', 'company', 'location', 'description'];

    protected $dates = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function workable()
    {
        return $this->morphTo();
    }
}
