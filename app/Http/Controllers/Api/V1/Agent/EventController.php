<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\V1\Agent\Event\{StoreEventRequest, UpdateEventRequest};
use App\Http\Resources\Api\V1\{Agent\EventResource, ErrorResource, SuccessResource};
use Illuminate\Support\Facades\{DB, Log};
use App\Models\Event;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiPermission:List Bookings,agent')->only('index');
        $this->middleware('apiPermission:Create Bookings,agent')->only('store');
        $this->middleware('apiPermission:View Bookings|Show Bookings,agent')->only('show');
        $this->middleware('apiPermission:Update Bookings|Edit Bookings,agent')->only('update');
//        $this->middleware('apiPermission:Delete Bookings,agent')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
    public function index()
    {
        $events = QueryBuilder::for(Event::class)
            ->allowedFilters(['agent_id', 'city_id', 'name', 'description', 'lat', 'lng', 'start_at', 'end_at'])
            ->allowedIncludes(['days', 'country', 'city', 'company', 'sessions', 'orders'])
            ->when(request()->has('sort_by'), fn($q) => $q->orderBy(request()->sort_by, request()->has('sort_type') ? request()->sort_type : 'asc'))
            ->paginate(config('app.pagination'));
        return count($events) > 0
            ? EventResource::collection($events)
            : EventResource::collection($events)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.events')])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEventRequest $request
     * @return SuccessResource | ErrorResource
     */
    public function store(StoreEventRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = !$request->has('agent_id') ? $request->safe()->merge(['agent_id' => auth('agent_api')->id()])->all() : $request->safe()->all();
            if ($request->safe()->days_type !== 'multi') $validated['end_at'] = Carbon::parse($validated['start_at'])->addDay();
            $event = Event::create($validated);
            if ($request->has('days')) {
                $event->days()->createMany($request->days)
                    ->each(function ($day, $index) use ($request) {
                        if ($request->has("days.$index.sessions") && !empty($request->days[$index]['sessions'])) {
                            $day->sessions()->createMany($request->days[$index]['sessions']);
                        }
                    });
            }
            if ($request->has('cover')) $event->addMediaFromRequest('cover')->toMediaCollection('event_cover_image');
            DB::commit();
            return SuccessResource::make(__('messages.success.created', ['attribute' => __('attributes.event')]), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in EventController@store: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.failed.created', ['attribute' => __('attributes.event')]), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return EventResource | ErrorResource
     */
    public function show($id)
    {
        return ($event = Event::find($id))
            ? (request()->has('simple') && request()->simple === 'true'
                ? EventResource::make($event)
                : EventResource::make($event->load('days', 'country', 'city', 'agent')))
            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.event')]), 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEventRequest $request
     * @param int $id
     * @return SuccessResource | ErrorResource
     */
    public function update(UpdateEventRequest $request, $id)
    {
        try {
            if (!empty($request->safe()->all())) {
                if ($event = Event::find($id)) {
                    DB::beginTransaction();
                    if ($request->safe()->days_type !== 'multi' && is_null($event->end_at)) $request->merge(['end_at' => Carbon::parse($request->start_at)->addDay()]);
                    $event->update($request->safe()->all());
                    if ($request->has('days')) {
                        $event->days()->delete();
                        $event->days()->createMany($request->days)
                            ->each(function ($day, $index) use ($request) {
                                if ($request->has("days.$index.sessions") && !empty($request->days[$index]['sessions'])) {
                                    $day->sessions()->delete();
                                    $day->sessions()->createMany($request->days[$index]['sessions']);
                                }
                            });
                    }
                    if ($request->has('cover')) $event->addMediaFromRequest('cover')->toMediaCollection('event_cover_image');
                    DB::commit();
                    return SuccessResource::make(__('messages.success.updated', ['attribute' => __('attributes.event')]));
                }
                return ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.event')]), 404);
            }
            return ErrorResource::make(__('messages.missing_data'), 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in EventController@update: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.failed.updated', ['attribute' => __('attributes.event')]), 500);
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
            if ($event = Event::find($id)) {
                DB::beginTransaction();
                $event->delete();
                $event->days()->delete();
                $event->clearMediaCollection('event')->where('model_id', $event->id);
                DB::commit();
                return SuccessResource::make(__('messages.success.deleted', ['attribute' => __('attributes.event')]));
            }
            return ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.event')]), 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in EventController@destroy: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.failed.deleted', ['attribute' => __('attributes.event')]), 500);
        }
    }
}
