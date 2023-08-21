<?php

namespace App\Http\Requests\Api\V1\Agent\Order;

use App\Exceptions\ApiAuthenticationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('agent_api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "event_id"     => ["required", "exists:events,id"],
            "tourguide_id" => ["required", "exists:tourguides,id"],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            "event_id.required"     => __("validation.required"),
            "event_id.exists"       => __("validation.exists"),
            "tourguide_id.required" => __("validation.required"),
            "tourguide_id.exists"   => __("validation.exists"),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            "event_id"     => __("attributes.event"),
            "tourguide_id" => __("attributes.tourguide"),
        ];
    }

    /**
     * @throws ApiAuthenticationException
     */
    protected function failedAuthorization()
    {
        throw new ApiAuthenticationException(__('auth.user_not_authorized'), 403);
    }
}
