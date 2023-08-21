<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\{Agent\TourguideResource, ErrorResource};
use App\Models\Tourguide;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TourguideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
    public function index()
    {
        $tourguides = QueryBuilder::for(Tourguide::class)
            ->allowedFilters(['first_name', 'last_namr', 'email', 'username', 'country_id', 'company_id', 'birtdate', 'age', 'education',
                'years_of_experience', 'is_online', 'is_active', 'gender',
                AllowedFilter::callback('newest', fn($query, $value, $property) => $query->when(is_bool($value) && $value, fn($query) => $query->whereDate('created_at', '>=', now()->subDays(7)), fn($query) => $query->whereDate('created_at', '<=', now()->subDays(7)))),
                AllowedFilter::callback('rates', fn($query, $value, $property) => $query->when($value > 0 && $value <= 5, fn($query) => $query->whereHas('rates', fn($q) => $q->where('rate', $value)))),
                AllowedFilter::callback('languages', fn($query, $value, $property) => $query->when($value, fn($query) => $query->whereHas('languages', fn($q) => $q->whereIn('languages.id', is_array($value) ? $value : [$value])))),
                AllowedFilter::callback('skills', fn($query, $value, $property) => $query->when($value, fn($query) => $query->whereHas('skills', fn($q) => $q->whereIn('skills.id', is_array($value) ? $value : [$value])))),
                AllowedFilter::callback('location', fn($query, $value, $property) => $query->whereHas('city', fn($query) => $query->whereIn('id', is_array($value) ? $value : [$value]))),
            ])
            ->allowedIncludes(['country', 'phones', 'work_experiences', 'languages', 'skills', 'certificates'])
            ->paginate(config('app.pagination'));

        return count($tourguides) > 0
            ? TourguideResource::collection($tourguides)
            : TourguideResource::collection($tourguides)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.tourguides')])]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return TourguideResource | ErrorResource
     */
    public function show($id)
    {
        return ($tourguide = Tourguide::find($id))
            ? request()->has('simple') && request()->simple === 'true'
                ? TourguideResource::make($tourguide)
                : TourguideResource::make($tourguide->load('country', 'phones'))
            : ErrorResource::make(__('messages.no_data', ['attribute' => __('attributes.tourguide')]), 404);
    }
}
