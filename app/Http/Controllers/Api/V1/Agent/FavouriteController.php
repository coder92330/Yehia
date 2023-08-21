<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\{Agent\TourguideResource, ErrorResource, SuccessResource};
use App\Http\Requests\Api\V1\Agent\Favourite\StoreFavouriteRequest;
use App\Models\Tourguide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
    public function index()
    {
        $favTourguides = Tourguide::favouritedBy(auth('agent_api')->user())->paginate(config('app.pagination'));
        return count($favTourguides) > 0
            ? TourguideResource::collection($favTourguides)
            : TourguideResource::collection($favTourguides)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.favourite_tourguides')])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFavouriteRequest $request
     * @return ErrorResource | SuccessResource
     */
    public function store(StoreFavouriteRequest $request)
    {
        try {
            if (!auth('agent_api')->user()->favourites()->where(['favouritable_id' => $request->tourguide_id, 'favouritable_type' => Tourguide::class])->exists()) {
                auth('agent_api')->user()->favourites()->create([
                    'favouritable_id' => $request->tourguide_id,
                    'favouritable_type' => Tourguide::class,
                ]);
                return SuccessResource::make(__('messages.tourguide_added_to_favourite'), 201);
            }
            return ErrorResource::make(__('messages.tourguide_already_favourite'), 500);
        } catch (\Exception $e) {
            Log::channel('agent')->error("Error in FavouriteController@store: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.error_occurred'), 500);
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
            if (auth('agent_api')->user()->favourites()->where(['favouritable_id' => $id, 'favouritable_type' => Tourguide::class])->exists()) {
                auth('agent_api')->user()->favourites()->where(['favouritable_id' => $id, 'favouritable_type' => Tourguide::class])->delete();
                return SuccessResource::make(__('messages.tourguide_removed_from_favourite'));
            }
            return ErrorResource::make(__('messages.tourguide_not_favourite'), 500);
        } catch (\Exception $e) {
            Log::channel('agent')->error("Error in FavouriteController@destroy: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('messages.error_occurred'), 500);
        }
    }
}
