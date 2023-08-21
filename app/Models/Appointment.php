<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourguide_id',
        'start_at',
        'end_at',
    ];

    protected $appends = [
        'title',
        'start',
        'end',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function tourguide()
    {
        return $this->belongsTo(Tourguide::class);
    }

    public function getTitleAttribute()
    {
        return 'Available For Booking';
    }

    public function getStartAttribute()
    {
        return $this->start_at->format('Y-m-d H:i:s');
    }

    public function getEndAttribute()
    {
        return $this->end_at->format('Y-m-d H:i:s');
    }
}
