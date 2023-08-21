<?php

namespace App\Http\Controllers\Api\V1\Tourguide;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Tourguide\Setting\UpdateSettingRequest;
use App\Http\Resources\Api\V1\ErrorResource;
use App\Http\Resources\Api\V1\SuccessResource;
use App\Http\Resources\Api\V1\Tourguide\PageResource;
use App\Http\Resources\Api\V1\Tourguide\SettingsResource;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Show the specified resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection | ErrorResource
     */
    public function index()
    {
        return ($settings = auth('tourguide_api')->user()->settings)
            ? SettingsResource::collection($settings)
            : ErrorResource::make(__('messages.not_found', ['attribute' => __('attributes.settings')]), 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSettingRequest $request
     * @return SuccessResource | ErrorResource
     */
    public function update(UpdateSettingRequest $request): SuccessResource|ErrorResource
    {
        try {
            if (!empty($request->safe()->all())) {
                DB::beginTransaction();
                auth('tourguide_api')->user()->settings()
                    ->whereIn('key', $request->keys())
                    ->each(fn($setting) => $setting->pivot->update(['value' => $request->get($setting->key)]));
                DB::commit();
                return SuccessResource::make(__('messages.success.updated', ['attribute' => __('attributes.settings')]));
            }
            return ErrorResource::make(__('messages.missing_data'), 400);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('tourguide')->error("Error in SettingsController@update: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make($e->getMessage(), 500);
        }
    }

    public function termsAndConditions(): PageResource|ErrorResource
    {
        return ($termsAndConditions = Page::where('slug', 'terms_and_conditions')->first() ?? 'Terms and Conditions')
            ? PageResource::make($termsAndConditions)
            : PageResource::make($termsAndConditions)->additional(['message' => __('messages.not_found', ['attribute' => __('attributes.terms_and_conditions')])]);
    }

    public function privacyPolicy(): PageResource|ErrorResource
    {
        return ($privacyPolicy = Page::where('slug', 'privacy_policy')->first() ?? 'Privacy Policy')
            ? PageResource::make($privacyPolicy)
            : PageResource::make($privacyPolicy)->additional(['message' => __('messages.not_found', ['attribute' => __('attributes.privacy_policy')])]);
    }

    public function aboutUs(): PageResource|ErrorResource
    {
        return ($aboutUs = Page::where('slug', 'about_us')->first() ?? 'About Us')
            ? PageResource::make($aboutUs)
            : PageResource::make($aboutUs)->additional(['message' => __('messages.not_found', ['attribute' => __('attributes.about_us')])]);
    }

    public function contactUs(): PageResource|ErrorResource
    {
        return ($contactUs = Page::where('slug', 'contact_us')->first() ?? 'Contact Us')
            ? PageResource::make($contactUs)
            : PageResource::make($contactUs)->additional(['message' => __('messages.not_found', ['attribute' => __('attributes.contact_us')])]);
    }

    public function faq(): PageResource|ErrorResource
    {
        return ($faq = Page::where('slug', 'faq')->first() ?? 'FAQ')
            ? PageResource::make($faq)
            : PageResource::make($faq)->additional(['message' => __('messages.not_found', ['attribute' => __('attributes.faq')])]);
    }
}
