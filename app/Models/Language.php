<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Language extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name'];

    public $translatable = ['name'];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function tourguides()
    {
        return $this->morphedByMany(Tourguide::class, 'languagable')
            ->withPivot('level', 'is_default')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'languagable')
            ->withPivot('level', 'is_default')
            ->withTimestamps();
    }

    public function agents()
    {
        return $this->morphedByMany(Agent::class, 'languagable')
            ->withPivot('level', 'is_default')
            ->withTimestamps();
    }

    // language_id doesn't exists in languables table

}
