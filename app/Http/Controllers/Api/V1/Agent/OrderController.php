<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Facades\Firebase;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Tourguide;
use App\Notifications\DatabaseNotification;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\V1\Agent\Order\{StoreOrderRequest, UpdateOrderRequest};
use App\Http\Resources\Api\V1\{Agent\OrderResource, SuccessResource, ErrorResource};
use Illuminate\Support\Facades\{DB, Log};
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiPermission:List Confirmed Bookings,agent')->only('index');
        $this->middleware('apiPermission:Create Confirmed Bookings,agent')->only('store');
        $this->middleware('apiPermission:View Confirmed Bookings|Show Confirmed Bookings,agent')->only('show');
        $this->middleware('apiPermission:Update Confirmed Bookings|Edit Bookings,agent')->only('update');
//        $this->middleware('apiPermission:Delete Confirmed Bookings,agent')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
    public function index()
    {
        $orders = QueryBuilder::for(auth('agent_api')->user()->orders()
            ->with(['event', 'tourguides'])
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->whereHas('tourguides', fn($q) => $q->where([['status', 'approved'], ['agent_status', 'approved']])))
            ->allowedIncludes(['event', 'tourguides'])
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
            ->paginate(config('app.pagination'));
        return count($orders) > 0
            ? OrderResource::collection($orders)
            : OrderResource::collection($orders)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.orders')])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderRequest $request
     * @return SuccessResource | ErrorResource
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $event_id = $request->safe()->event_id;
            if (($tourguide = Tourguide::find($request->safe()->tourguide_id)) && $tourguide->canAssignToEvent($event_id)) {
                if ($tourguide->availableForBooking($event_id)) {
                    $checkTourguide = auth('agent_api')->user()->orders()
                        ->where('event_id', $event_id)
                        ->whereHas('tourguides', fn($q) => $q->where('tourguide_id', $request->safe()->tourguide_id))
                        ->doesntExist();
                    if ($checkTourguide) {
                        DB::beginTransaction();
                        $order = auth('agent_api')->user()->orders()->where('event_id', $event_id)->first()
                            ?? auth('agent_api')->user()->orders()->create(['event_id' => $event_id]);
                        $order->tourguides()->attach($request->safe()->tourguide_id);
                        DB::commit();
                        $tourguide = Tourguide::find($request->safe()->tourguide_id);
                        $tourguide->notify(new DatabaseNotification(
                            __('notifications.booking.created.title'),
                            __('notifications.booking.created.body', ['event' => $order->event->name, 'user' => auth('agent_api')->user()->name,]),
                            'order',
                            ['id' => $order->id],
                        ));
                        Firebase::withTitle(__('notifications.booking.created.title'))
                            ->withToken($tourguide->device_key)
                            ->withAdditionalData(['id' => $order->id, 'type' => 'order'])
                            ->withBody(__('notifications.booking.created.body', ['user' => auth('agent_api')->user()->full_name, 'event' => $order->event->name]))
                            ->send();
                        return SuccessResource::make(__('messages.success.created', ['attribute' => __('attributes.order')]), 201);
                    }
                    return ErrorResource::make(__('notifications.booking.tourguide-already-booked'), 422);
                }
                return ErrorResource::make(__('notifications.booking.tourguide-not-available'), 422);
            }
            return ErrorResource::make(__('notifications.booking.tourguide-cant-assign-to-event', ['day_type' => Event::find($event_id)->days()?->pluck('type')->unique()->first()]), 422);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::channel('agent')->error("Error in OrderController@store: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.failed.created', ['attribute' => __('attributes.order')]), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return OrderResource | ErrorResource
     */
    public function show($id)
    {
        return ($order = auth('agent_api')->user()->orders()->find($id))
            ? request()->has('simple') && request()->simple === 'true'
                ? OrderResource::make($order)
                : OrderResource::make($order->load('event', 'event.agent', 'tourguides'))
            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.order')]), 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderRequest
     * @param int $id
     * @return SuccessResource | ErrorResource
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        try {
            if (!empty($request->safe()->all())) {
                if ($order = auth('agent_api')->user()->orders()->find($id)) {
                    if ($order->tourguides()->wherePivot('tourguide_id', $request->safe()->tourguide_id)->first()->pivot->agent_status === 'pending') {
                        DB::beginTransaction();
                        $order->tourguides()->updateExistingPivot($request->safe()->tourguide_id, ['agent_status' => $request->safe()->status]);
                        DB::commit();
                        $tourguide = Tourguide::find($request->safe()->tourguide_id);
                        $tourguide->notify(new DatabaseNotification(
                            $request->safe()->status === 'approved'
                                ? __('notifications.booking.approved.title')
                                : __('notifications.booking.rejected.title'),
                            $request->safe()->status === 'approved'
                                ? __('notifications.booking.approved.body', ['event' => $order->event->name, 'user' => ucwords(auth('agent_api')->user()->full_name)])
                                : __('notifications.booking.rejected.body', ['event' => $order->event->name, 'user' => ucwords(auth('agent_api')->user()->full_name)]),
                            'order',
                            ['id' => $order->id],
                        ));
                        Firebase::withTitle(__('notifications.order_status_title'))
                            ->withModel($tourguide)
                            ->withAdditionalData($order)
                            ->withBody(__('notifications.order_status_body', [
                                'name' => auth('agent_api')->user()->full_name,
                                'event' => $order->event->name,
                                'status' => $request->safe()->status
                            ]))->send();
                        return SuccessResource::make(__('messages.success.updated', ['attribute' => __('attributes.order')]));
                    }
                    return ErrorResource::make(__('messages.order_status_already_updated'), 422);
                }
                return ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.order')]), 404);
            }
            return ErrorResource::make(__('messages.missing_data'), 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in OrderController@update: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.failed.updated', ['attribute' => __('attributes.order')]), 500);
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
            if ($order = auth('agent_api')->user()->orders()->find($id)) {
                $order->tourguides()->detach();
                $order->delete();
                return SuccessResource::make(__('messages.success.deleted', ['attribute' => __('attributes.order')]));
            }
            return ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.order')]), 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in OrderController@destroy: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.failed.deleted', ['attribute' => __('attributes.order')]), 500);
        }
    }

    /**
     * List of all orderedBookings for the authenticated agent.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function orderedBookings()
    {
        $orders = QueryBuilder::for(auth('agent_api')->user()->orders()
            ->with(['event', 'tourguides'])
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->whereHas('tourguides', fn($q) => $q->where('status', '!=', 'approved')->orWhere('agent_status', '!=', 'approved')))
            ->allowedIncludes(['event', 'tourguides'])
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
            ->paginate(config('app.pagination'));

        return count($orders) > 0
            ? OrderResource::collection($orders)
            : OrderResource::collection($orders)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.ordered_bookings')])]);
    }
}
