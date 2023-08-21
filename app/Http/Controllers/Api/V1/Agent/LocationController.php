<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Agent\CityResource;
use App\Http\Resources\Api\V1\Agent\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function countries()
    {
        $countries = Country::when(request()->has('per_page'), fn($q) => $q->paginate(config('app.pagination')), fn($q) => $q->get());
        return $countries->isNotEmpty()
            ? CountryResource::collection($countries)
            : CountryResource::collection($countries)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.countries')])]);
    }

    public function cities($country_id)
    {
        if ($country = Country::find($country_id)) {
            $cities = $country->cities()->when(request()->has('per_page'), fn($q) => $q->paginate(config('app.pagination')), fn($q) => $q->get());
            return $cities->isNotEmpty()
                ? CityResource::collection($cities)
                : CityResource::collection($cities)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.cities')])]);
        }
        return CountryResource::collection(Country::where('id', $country_id)->paginate(config('app.pagination')))
            ->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.country')])]);
    }
}
