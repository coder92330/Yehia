<?php

namespace App\Http\Controllers\Api\V1\Tourguide;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tourguide\Message\StoreMessageRequest;
use App\Http\Resources\Api\V1\Tourguide\ChatResource;
use App\Http\Resources\Api\V1\ErrorResource;
use App\Http\Resources\Api\V1\SuccessResource;
use App\Http\Resources\Api\V1\Tourguide\MessageResource;
use App\Models\Agent;
use App\Models\Chat\Message;
use App\Models\Tourguide;
use App\Models\Chat\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ChatController extends Controller
{
    private function getChat($relation = null)
    {
        $relations = [
            "event",
            "members" => fn($q) => $q->where('memberable_type', '!=', auth('tourguide_api')->user()->getMorphClass())->with('memberable')
        ];

        if ($relation) {
            if (is_array($relation)) {
                $relations = array_merge($relations, $relation);
            } else {
                $relations[] = $relation;
            }
        }

        return QueryBuilder::for(auth('tourguide_api')->user()->rooms()->with($relations))
            ->allowedFilters(['event.name', AllowedFilter::callback('full_name', function ($query, $value) {
                $query->whereHas('members', function ($q) use (&$value) {
                    $q->whereHasMorph('memberable', [Tourguide::class], function ($q) use (&$value) {
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
     * @param int $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
    public function show($id)
    {
        $room = Room::whereHas('members', function ($q) {
            $q->where([['memberable_type', Tourguide::class], ['memberable_id', auth('tourguide_api')->id()]]);
        })->find($id);

        if ($room) {
            $messgaes = Message::whereRelation('room', 'id', $id)->with(['sender.memberable'])->latest()->paginate(config('app.pagination'));

            return count($messgaes) > 0
                ? MessageResource::collection($messgaes)->additional([
                    'id'         => $id,
                    'members'    => $room->members->pluck('memberable'),
                    'created_at' => $room->created_at,
                    'updated_at' => $room->updated_at,
                ])
                : MessageResource::collection($messgaes)->additional([
                    'message'    => __('messages.no_data', ['attribute' => __('attributes.messages')]),
                    'id'         => $id,
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
     * @param $room_id
     * @return SuccessResource | ErrorResource
     */
    public function store(StoreMessageRequest $request, $room_id)
    {
        try {
            DB::beginTransaction();
            if ($room = auth('tourguide_api')->user()->rooms()->find($room_id)) {
                $message = $room->messages()->create([
                    'sender_id' => $room->members()->whereMe('tourguide_api')->pluck('id')->first(),
                    'body'      => $request->message,
                ]);
                event(new MessageSent($room_id, auth('tourguide_api')->user(), $message, 'tourguide'));
            }
            DB::commit();
            return SuccessResource::make('Message sent successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorResource::make($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return SuccessResource | ErrorResource
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            if ($room = Room::find($id)) {
                $room->delete();
                $room->members()->delete();
                $room->messages()->delete();
                DB::commit();
                return SuccessResource::make('Chat deleted successfully', 200);
            }
            return ErrorResource::make('Chat not found', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return ErrorResource::make($e->getMessage(), 500);
        }
    }
}
