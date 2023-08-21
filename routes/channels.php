<?php

use App\Models\Chat\Room;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.{model}.{id}', function ($user, $model, $id) {
    return (int)$user->id === (int)$id && class_basename($user) === $model;
}, ['guards' => ['tourguide', 'agent', 'sanctum', 'web']]);

Broadcast::channel('room.{id}', function ($user, $room_id) {
    return Room::where('id', $room_id)
        ->whereHas('members', function ($query) use ($user) {
            $query->where([
                ['memberable_id', $user->id],
                ['memberable_type', get_class($user)]
            ]);
        })->exists();
}, ['guards' => ['tourguide', 'agent', 'sanctum', 'web']]);
