<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Agent\Company\UpdateCompanyRequest;
use App\Http\Requests\Api\V1\Agent\Profile\UpdateProfileRequest;
use App\Http\Resources\Api\V1\Agent\AgentResource;
use App\Http\Resources\Api\V1\Agent\CertificateResource;
use App\Http\Resources\Api\V1\Agent\CompanyResource;
use App\Http\Resources\Api\V1\Agent\LanguageResource;
use App\Http\Resources\Api\V1\Agent\SkillResource;
use App\Http\Resources\Api\V1\ErrorResource;
use App\Http\Resources\Api\V1\SuccessResource;
use App\Models\Certificate;
use App\Models\Language;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class ProfileController extends Controller
{
    /**
     * Show the specified resource.
     *
     * @return AgentResource | ErrorResource
     */
    public function index(): AgentResource|ErrorResource
    {
        return ($profile = auth('agent_api')->user())
            ? AgentResource::make($profile)
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
                auth('agent_api')->user()->update($request->safe()->all());
                if ($request->has('avatar')) auth('agent_api')->user()->addMediaFromRequest('avatar')->toMediaCollection('avatar');
                DB::commit();
                return SuccessResource::make(__('messages.success.updated', ['attribute' => __('attributes.profile')]));
            }
            return ErrorResource::make(__('messages.missing_data'), 400);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in ProfileController@update: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make($e->getMessage(), 500);
        }
    }

    /**
     * Show the specified resource.
     *
     * @return CompanyResource | ErrorResource
     */
    public function myCompany(): CompanyResource|ErrorResource
    {
        return ($myCompany = auth('agent_api')->user()->company)
            ? CompanyResource::make($myCompany)
            : ErrorResource::make(__('messages.no_data'), 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCompanyRequest $request
     * @return SuccessResource | ErrorResource
     */
    public function updateMyCompany(UpdateCompanyRequest $request): SuccessResource|ErrorResource
    {
        try {
            if (!empty($request->safe()->all())) {
                DB::beginTransaction();
                auth('agent_api')->user()->company()->update($request->safe()->all());
                if ($request->has('logo')) auth('agent_api')->user()->company->addMediaFromRequest('logo')->toMediaCollection('logo');
                DB::commit();
                return SuccessResource::make(__('messages.success.updated', ['attribute' => __('attributes.company.name')]));
            }
            return ErrorResource::make(__('messages.missing_data'), 400);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('agent')->error("Error in ProfileController@updateMyCompany: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make($e->getMessage(), 500);
        }
    }

    public function skills()
    {
        $skills = Skill::paginate(config('app.pagination'));
//        dd($skills->toArray());
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
