<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room_id;
    public $user;
    public $message;
    public $guard;

    public function __construct($room_id, $user, $message, $guard)
    {
        $this->room_id = $room_id;
        $this->user    = $user;
        $this->message = $message;
        $this->guard   = $guard;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("room.$this->room_id");
    }

    public function broadcastWith()
    {
        return [
            'id'         => $this->message->id,
            'room'       => $this->room_id,
            'from_user'  => $this->user,
            'message'    => $this->message->body,
            'guard'      => $this->guard,
            'created_at' => $this->message->created_at,
            'updated_at' => $this->message->updated_at,
        ];
    }
}
