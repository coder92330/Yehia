<?php

namespace App\Http\Controllers\Api\V1\Tourguide;

use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\V1\Tourguide\Event\{StoreEventRequest, UpdateEventRequest};
use App\Http\Resources\Api\V1\{Tourguide\EventResource, ErrorResource, SuccessResource, Tourguide\OrderResource};
use Illuminate\Support\Facades\{DB, Log};
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
//    public function index()
//    {
//        $events = QueryBuilder::for(Event::whereHas('orders', function ($query) {
//            $query->whereHas('tourguides', function ($q) {
//                $q->where([
//                    'tourguide_id' => auth('tourguide_api')->id(),
//                    'status' => 'approved',
//                    'agent_status' => 'approved'
//                ]);
//            });
//        }))
//            ->allowedIncludes(['event', 'tourguides'])
//            ->allowedFilters([
//                'event_id', 'event.name', 'event.description', 'event.lat', 'event.lng', 'event.start_at', 'event.end_at',
//                'tourguides.first_name', 'tourguides.last_name', 'tourguides.email', 'tourguides.phone', 'tourguides.bio',
//                'tourguides.country_id', 'tourguides.city_id', 'tourguides.address', 'tourguides.lat', 'tourguides.lng', 'tourguides.rate'])
//            ->when(request()->has('sort_by'), fn($q) => $q->orderBy(request()->sort_by, request()->has('sort_type') ? request()->sort_type : 'asc'))
//            ->paginate(config('app.pagination'));
//
//        return count($events) > 0
//            ? EventResource::collection($events)
//            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.events')]), 404);
//    }

    public function index()
    {
        $orders = QueryBuilder::for(auth('tourguide_api')->user()->orders()
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->with('event', 'event.days')
            ->whereHas('tourguides', function ($q) {
                $q->where([
                    'tourguide_id' => auth('tourguide_api')->id(),
                    'status' => 'approved',
                    'agent_status' => 'approved'
                ]);
            }))
            ->allowedIncludes(['event'])
            ->allowedFilters([
                'event_id', 'event.name', 'event.description', 'event.lat', 'event.lng', 'event.start_at', 'event.end_at',
                'tourguides.first_name', 'tourguides.last_name', 'tourguides.email', 'tourguides.phone', 'tourguides.bio',
                'tourguides.country_id', 'tourguides.city_id', 'tourguides.address', 'tourguides.lat', 'tourguides.lng', 'tourguides.rate'])
            ->when(request()->has('sort_by'), function ($q) {
                $sort_by = request()->sort_by === 'name' ? 'events.name' : "orders." . request()->sort_by;
                $sort_type = request()->has('sort_type') ? request()->sort_type : 'asc';
                $q->orderBy($sort_by, $sort_type);
            })
            ->select('orders.*')
            ->when(!request()->has('per_page'), fn($q) => $q->get(), fn($q) => $q->paginate(config('app.pagination')));

        return count($orders) > 0
            ? OrderResource::collection($orders)
            : OrderResource::collection($orders)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.orders')])]);
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
            ? request()->has('simple') && request()->simple === 'true'
                ? EventResource::make($event)
                : EventResource::make($event->load('days', 'country', 'agent', 'company'))
            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.event')]), 404);
    }
}
