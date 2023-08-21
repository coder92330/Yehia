<?php

namespace App\Http\Controllers\Api\V1\Tourguide;

use App\Facades\Firebase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tourguide\Order\UpdateOrderRequest;
use App\Notifications\DatabaseNotification;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Api\V1\{Tourguide\OrderResource, SuccessResource, ErrorResource};
use Illuminate\Support\Facades\{DB, Log};
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
    public function index()
    {
        $orders = QueryBuilder::for(auth('tourguide_api')->user()->orders()
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->with('event', 'event.days')
            ->whereHas('tourguides', function ($q) {
                $q->where([['tourguide_id', auth('tourguide_api')->id()], ['agent_status', '!=', 'approved']]);
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
            ->paginate(config('app.pagination'));

        return count($orders) > 0
            ? OrderResource::collection($orders)
            : OrderResource::collection($orders)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.orders')])]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return OrderResource | ErrorResource
     */
    public function show($id)
    {
        return ($order = auth('tourguide_api')->user()->orders()->find($id))
            ? request()->has('simple') && request()->simple === 'true'
                ? OrderResource::make($order)
                : OrderResource::make($order->load('event', 'event.agent', 'tourguides'))
            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.order')]), 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOrderRequest $request
     * @param int $id
     * @return SuccessResource | ErrorResource
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        try {
            if (!empty($request->safe()->all())) {
                if ($order = auth('tourguide_api')->user()->orders()->find($id)) {
                    if ($order->pivot->agent_status === 'pending') {
                        if ($order->pivot->status === 'pending') {
                            DB::beginTransaction();
                            $order->tourguides()->updateExistingPivot(auth('tourguide_api')->id(), ['status' => $request->safe()->status]);
                            DB::commit();
                            $order->agent->notify(new DatabaseNotification(
                                $request->safe()->status === 'approved'
                                    ? __('notifications.booking.approved.title')
                                    : __('notifications.booking.rejected.title'),
                                $request->safe()->status === 'approved'
                                    ? __('notifications.booking.approved.body', ['event' => $order->event->name, 'user' => ucwords(auth('tourguide_api')->user()->full_name)])
                                    : __('notifications.booking.rejected.body', ['event' => $order->event->name, 'user' => ucwords(auth('tourguide_api')->user()->full_name)]),
                                'order',
                                ['id' => $order->id],
                            ));
                            Firebase::withTitle(__('notifications.order_status_title'))
                                ->withModel($order->agent)
                                ->withAdditionalData($order)
                                ->withBody(__('notifications.order_status_body', [
                                    'name' => auth('tourguide_api')->user()->full_name,
                                    'event' => $order->event->name,
                                    'status' => $request->safe()->status
                                ]))->send();
                            return SuccessResource::make(__('messages.success.updated', ['attribute' => __('attributes.order')]));
                        }
                        return ErrorResource::make(__('messages.order_status_already_updated'), 422);
                    }
                    return ErrorResource::make(__("messages.order_{$order->pivot->agent_status}_by_agent"), 422);
                }
                return ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.order')]), 404);
            }
            return ErrorResource::make(__('messages.missing_data'), 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('tourguide')->error("Error in OrderController@update: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.failed.updated', ['attribute' => __('attributes.order')]), 500);
        }
    }
}
