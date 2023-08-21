<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Skill extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name'];

    public $translatable = ['name'];

    public function tourguides()
    {
        return $this->morphedByMany(Tourguide::class, 'skillable')
            ->withPivot('level')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'skillable')
            ->withPivot('level')
            ->withTimestamps();
    }

    public function agents()
    {
        return $this->morphedByMany(Agent::class, 'skillable')
            ->withPivot('level')
            ->withTimestamps();
    }
}
