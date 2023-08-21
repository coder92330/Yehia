<?php

namespace App\Http\Controllers\Api\V1\Tourguide;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tourguide\Profile\UpdateProfileRequest;
use App\Models\Language;
use App\Models\Skill;
use App\Http\Resources\Api\V1\{Tourguide\LanguageResource,
    Tourguide\SkillResource,
    ErrorResource,
    SuccessResource,
    Tourguide\TourguideResource};
use Illuminate\Support\Facades\{DB, Log};

class ProfileController extends Controller
{
    /**
     * Show the specified resource.
     *
     * @return TourguideResource | ErrorResource
     */
    public function index(): TourguideResource
    {
        return ($profile = auth('tourguide_api')->user())
            ? TourguideResource::make($profile)
            : ErrorResource::make(__('messages.no_data'), 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProfileRequest $request
     * @return SuccessResource | ErrorResource
     */
    public function update(UpdateProfileRequest $request): SuccessResource|ErrorResource
    {
        try {
            if (!empty($request->safe()->all())) {
                DB::beginTransaction();
                auth('tourguide_api')->user()->update($request->safe()->all());
                if ($request->has('avatar')) auth('tourguide_api')->user()->addMediaFromRequest('avatar')->toMediaCollection('avatar');
                DB::commit();
                return SuccessResource::make(__('messages.success.updated', ['attribute' => __('attributes.profile')]));
            }
            return ErrorResource::make(__('messages.missing_data'), 400);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('tourguide')->error("Error in ProfileController@update: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make($e->getMessage(), 500);
        }
    }

    public function skills()
    {
        $skills = Skill::paginate(config('app.pagination'));
        return $skills->isNotEmpty()
            ? SkillResource::collection($skills)
            : SkillResource::collection($skills)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.skills')])]);
    }

    public function languages()
    {
        $languages = Language::paginate(config('app.pagination'));
        return $languages->isNotEmpty()
            ? LanguageResource::collection($languages)
            : LanguageResource::collection($languages)->additional(['message' => __('messages.no_data', ['attribute' => __('attributes.languages')])]);
    }
}
