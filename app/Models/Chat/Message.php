<?php

namespace App\Models\Chat;

use App\Models\Chat\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function sender()
    {
        return $this->belongsTo(Member::class, "sender_id", 'id');
    }

    public function scopeLastMessage($query, $room_id)
    {
        return $query->where('room_id', $room_id)->latest()->first();
    }
}
