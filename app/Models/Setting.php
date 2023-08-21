<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'label',
        'value',
        'type',
        'description',
        'group',
        'settingable_id',
        'settingable_type',
    ];

    public $translatable = ['label', 'value', 'description'];

    public function tourguides()
    {
        return $this->morphedByMany(Tourguide::class, 'settingable');
    }

    public function agents()
    {
        return $this->morphedByMany(Agent::class, 'settingable');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'settingable');
    }
}
