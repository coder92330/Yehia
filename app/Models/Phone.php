<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'phonable_id',
        'phonable_type',
        'number',
        'country_code',
        'type',
        'label',
        'is_primary',
        'is_active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function phonable()
    {
        return $this->morphTo();
    }

    protected function countryCode(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (str_starts_with($value, '+')) {
                    return $value;
                }
                if (str_starts_with($value, '00')) {
                    return '+' . substr($value, 2);
                }
                return '+' . $value;
            },
            set: function ($value) {
                if (str_starts_with($value, '+')) {
                    return $value;
                }
                if (str_starts_with($value, '00')) {
                    return '+' . substr($value, 2);
                }
                return '+' . $value;
            }
        );
    }

    protected function number(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (str_starts_with($value, $this->country_code)) {
                    return $value;
                }
                if (str_starts_with($value, '0')) {
                    return $this->country_code . substr($value, 1);
                }
                return $this->country_code . $value;
            },
            set: function ($value) {
                if (str_starts_with($value, $this->country_code)) {
                    return $value;
                }
                if (str_starts_with($value, '0')) {
                    return $this->country_code === '+' ? $value : $this->country_code . substr($value, 1);
                }
                return $this->country_code === '+' ? $value : $this->country_code . $value;
            }
        );
    }
}
