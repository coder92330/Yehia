<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Agent\Message\StoreMessageRequest;
use App\Http\Resources\Api\V1\Agent\ChatResource;
use App\Http\Resources\Api\V1\Agent\MessageResource;
use App\Http\Resources\Api\V1\Agent\MessageResourceCollection;
use App\Http\Resources\Api\V1\ErrorResource;
use App\Http\Resources\Api\V1\SuccessResource;
use App\Models\Agent;
use App\Models\Chat\Message;
use App\Models\Chat\Room;
use App\Models\Tourguide;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ChatController extends Controller
{
    private function getChat($relation = null)
    {
        $relations = [
            "event",
            "members" => fn($q) => $q->where('memberable_type', '!=', auth('agent_api')->user()->getMorphClass())->with('memberable')
        ];

        if ($relation) {
            if (is_array($relation)) {
                $relations = array_merge($relations, $relation);
            } else {
                $relations[] = $relation;
            }
        }

        $rooms = auth('agent_api')->user()->rooms()->with($relations)->whereHas('messages');
        return QueryBuilder::for($rooms)
            ->allowedFilters(['event.name', AllowedFilter::callback('full_name', function ($query, $value) {
                $query->whereHas('members', function ($q) use (&$value) {
                    $q->whereHasMorph('memberable', [Agent::class, Tourguide::class], function ($q) use (&$value) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$value%");
                    })
                        ->orWhereHasMorph('memberable', [User::class], fn($q) => $q->where('name', 'like', "%$value%"));
                });
            })])
            ->allowedIncludes(['event', 'members.memberable'])
            ->distinct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
    public function index()
    {
        $chats = $this->getChat('messages')->paginate(config('app.pagination'));
        return count($chats) > 0
            ? ChatResource::collection($chats)
            : ChatResource::collection($chats)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.chats')])]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $tourguide_id
     * @param int|null $event_id
     * @return AnonymousResourceCollection | ErrorResource
     */
    public function show(int $tourguide_id, int $event_id = null)
    {
        $user = (request()->chat_with === 'user')
            ? (User::find($tourguide_id) ?? ['id' => $tourguide_id, 'type' => User::class])
            : (Tourguide::find($tourguide_id) ?? ['id' => $tourguide_id, 'type' => Tourguide::class]);
        $room = (new Room)->getRoomBetween(auth('agent_api')->user(), $user, $event_id);

        if ($room) {
            $messgaes  = Message::whereRelation('room', 'id', $room->id)->with(['sender.memberable'])->latest()->paginate(config('app.pagination'));
            return count($messgaes) > 0
                ? MessageResource::collection($messgaes)->additional([
                    'id'         => $room->id,
                    'members'    => $room->members->pluck('memberable'),
                    'created_at' => $room->created_at,
                    'updated_at' => $room->updated_at,
                ])
                : MessageResource::collection($messgaes)->additional([
                    'message'    => __('messages.no_data', ['attribute' => __('attributes.messages')]),
                    'id'         => $room->id,
                    'members'    => $room->members->pluck('memberable'),
                    'created_at' => $room->created_at,
                    'updated_at' => $room->updated_at,
                ]);
        }
        return ErrorResource::make(__('messages.chat.not_found'), 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMessageRequest $request
     * @param int $tourguide_id
     * @param int|null $event_id
     * @return SuccessResource | ErrorResource
     */
    public function store(StoreMessageRequest $request, $tourguide_id, $event_id = null)
    {
        try {
            DB::beginTransaction();
            $user = (request()->chat_with === 'user')
                ? (User::find($tourguide_id) ?? ['id' => $tourguide_id, 'type' => User::class])
                : (Tourguide::find($tourguide_id) ?? ['id' => $tourguide_id, 'type' => Tourguide::class]);
            if ($room = (new Room)->getRoomBetween(auth('agent_api')->user(), $user, $event_id)) {
                $message = $room->messages()->create([
                    'sender_id' => $room->members()->whereMe('agent_api')->pluck('id')->first(),
                    'body'      => $request->message
                ]);
                event(new MessageSent($room->id, auth('agent_api')->user(), $message, 'agent'));
            }
            DB::commit();
            return SuccessResource::make(__('messages.message_sent_successfully'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in ChatController@store: {$e->getMessage()} In File: {$e->getFile()} On Line: {$e->getLine()}");
            return ErrorResource::make(__('messages.something_went_wrong'), 500);
        }
    }
}
