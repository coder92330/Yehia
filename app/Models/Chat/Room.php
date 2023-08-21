<?php

namespace App\Models\Chat;

use App\Events\MessageSent;
use App\Mail\NewMessage;
use App\Models\Agent;
use App\Models\Chat\Member;
use App\Models\Chat\Message;
use App\Models\Event;
use App\Models\Tourguide;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Room extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeStaffChat($query)
    {
        return $query
            ->whereRelation('members', 'memberable_type', Tourguide::class)
            ->whereHas('members', function ($q) {
                $q->where([['memberable_type', Agent::class], ['memberable_id', '!=', auth('agent')->id()]])
                    ->whereHasMorph('memberable', Agent::class, function ($q) {
                        $q->whereRelation('company', 'id', auth('agent')->user()->company_id);
                    });
            });
    }

    public function getRoomBetween($firstUser, $secondUser, $event_id = null, $chat_with = null)
    {
        $intersectRooms = $firstUser->rooms()
            ->when(!empty($secondUser), function ($q) use ($secondUser) {
                $q->whereHas('members', function ($query) use ($secondUser) {
                    $query->where('memberable_id', $secondUser->id ?? $secondUser['id']);
                    $query->where('memberable_type', isset($secondUser) && !is_array($secondUser) ? get_class($secondUser) : $secondUser['type']);
                });
            })
            ->when(!is_null($event_id), fn($q) => $q->where('event_id', $event_id), fn($q) => $q->whereNull('event_id'))
            ->first();

        if ($chat_with === 'user' && (isset($secondUser) && !is_array($secondUser) ? get_class($secondUser) : $secondUser['type']) === User::class) {
            return $intersectRooms ?? abort(404, __('messages.chat.not_found'));
        }

        return $intersectRooms ?? $this->createRoomBetween($firstUser, $secondUser, $event_id);
    }

    public function createRoomBetween($firstUser, $secondUser, $event_id = null)
    {
        $room = Room::create(['event_id' => $event_id]);
        $room->members()->createMany([
            ['memberable_id' => $firstUser->id, 'memberable_type' => get_class($firstUser)],
            ['memberable_id' => $secondUser->id ?? $secondUser['id'],
                'memberable_type' => isset($secondUser) && !is_array($secondUser) ? get_class($secondUser) : $secondUser['type']]
        ]);
        return $room;
    }

    public function sendMsg(string $msg, $sender_id, $guard = null): void
    {
        $message = $this->messages()->create(['sender_id' => $sender_id, 'body' => $msg]);
        event(new MessageSent($this->id, $guard && $guard !== 'admin' ? auth($guard)->user() : auth()->user(), $message, $guard));
        $this->sendEmailNotification($message, $sender_id);
    }

    private function sendEmailNotification($message, $sender_id): void
    {
        $this->members()
            ->where([['memberable_id', '!=', $sender_id], ['memberable_type', '!=', User::class]])
            ->get()
            ->each(function ($member) use ($message) {
                if (isset($member->memberable) && $member->memberable->hasSetting('incoming_messages_notifications', true)->exists()) {
                    try {
                        Mail::to($member->memberable->email)
                            ->later(now()->addMinute(), new NewMessage($message, $member->memberable));
                    } catch (\Throwable $th) {
                        return true;
                    }
                }
                return true;
            });
    }

    public function unreadMessages($guard = null)
    {
        return $this->messages()->where([['is_read', false], ['sender_id', '!=', $this->getMemberId($guard)]]);
    }

    public function getMemberId($guard = null)
    {
        return $this->members()->where([
            ['memberable_id', auth($guard)->id()],
            ['memberable_type', auth($guard)->user()->getMorphClass()]
        ])->first()?->id;
    }

    public function authMemberId($guard)
    {
        return DB::raw("(SELECT id FROM members
                               WHERE memberable_id = " . auth($guard)->id() . "
                               AND memberable_type = '" . auth($guard)->user()->getMorphClass() . "'
                               AND room_id = rooms.id)");
    }

    public function scopeWithMembers($query, $authGuard, $reciverClasses, $relation = null)
    {
        $relationClasses = is_array($reciverClasses) ? array_merge($reciverClasses, [auth($authGuard)->user()->getMorphClass()]) : [$reciverClasses, auth($authGuard)->user()->getMorphClass()];
        $relations = ["event", "members" => fn($q) => $q->whereIn('memberable_type', $relationClasses)->with('memberable')];

        if ($relation) {
            if (is_array($relation)) {
                $relations = array_merge($relations, $relation);
            } else {
                $relations[] = $relation;
            }
        }

        return $query->with($relations)
            ->whereHas('members', fn($q) => $q->whereIn('memberable_type', is_array($reciverClasses) ? $reciverClasses : [$reciverClasses]))
            ->withCount(['messages as unread_messages' => fn($q) => $q->where([
                ['is_read', false],
                ['sender_id', '!=', $this->authMemberId($authGuard)],
            ])])
            ->distinct();
    }
}
