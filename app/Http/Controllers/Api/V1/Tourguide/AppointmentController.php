<?php

namespace App\Http\Controllers\Api\V1\Tourguide;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tourguide\Appointment\StoreAppointmentRequest;
use App\Http\Requests\Api\V1\Tourguide\Appointment\UpdateAppointmentRequest;
use App\Http\Resources\Api\V1\ErrorResource;
use App\Http\Resources\Api\V1\SuccessResource;
use App\Http\Resources\Api\V1\Tourguide\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \App\Http\Resources\Api\V1\ErrorResource
     */
    public function index()
    {
        $appointments = Appointment::where('tourguide_id', auth('tourguide_api')->id())->get();

        return count($appointments) > 0
            ? AppointmentResource::collection($appointments)
            : AppointmentResource::collection($appointments)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.appointments')])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAppointmentRequest $request
     * @return SuccessResource | ErrorResource
     */
    public function store(StoreAppointmentRequest $request)
    {
        try {
            DB::beginTransaction();
            Appointment::create($request->safe()->merge(['tourguide_id' => auth('tourguide_api')->id()])->all());
            DB::commit();
            return SuccessResource::make(__('messages.success.created', ['attribute' => __('attributes.appointment')]), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('tourguide')->error("Error in MailController@store: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.error_occurred'), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return AppointmentResource | ErrorResource
     */
    public function show($id)
    {
        return ($appointment = auth('tourguide_api')->user()->appointments()->find($id))
            ? AppointmentResource::make($appointment)
            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.appointment')]), 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAppointmentRequest $request
     * @param int $id
     * @return SuccessResource | ErrorResource
     */
    public function update(UpdateAppointmentRequest $request, $id)
    {
        try {
            if (!empty($request->safe()->all())) {
                if ($appointment = auth('tourguide_api')->user()->appointments()->find($id)) {
                    DB::beginTransaction();
                    $appointment->update($request->safe()->all());
                    DB::commit();
                    return SuccessResource::make(__('messages.success.updated', ['attribute' => __('attributes.appointment')]));
                }
                return ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.appointment')]), 404);
            }
            return ErrorResource::make(__('messages.missing_data'), 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('tourguide')->error("Error in AppointmentController@update: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.failed.updated', ['attribute' => __('attributes.appointment')]), 500);
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
        return (auth('tourguide_api')->user()->appointments()->find($id)->delete())
            ? SuccessResource::make(__('messages.success.deleted', ['attribute' => __('attributes.appointment')]))
            : ErrorResource::make(__('messages.failed.deleted', ['attribute' => __('attributes.appointment')]), 500);
    }
}
