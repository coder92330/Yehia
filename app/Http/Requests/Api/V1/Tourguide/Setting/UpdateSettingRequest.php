<?php

namespace App\Http\Requests\Api\V1\Tourguide\Setting;

use App\Exceptions\ApiAuthenticationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('tourguide_api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "my_profile_added_to_favorites"   => ["required", "boolean"],
            "get_rated"                       => ["required", "boolean"],
            "get_a_booking"                   => ["required", "boolean"],
            "receive_notifications"           => ["required", "boolean"],
            "incoming_messages_notifications" => ["required", "boolean"],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            "my_profile_added_to_favorites.required"   => __("messages.required"),
            "my_profile_added_to_favorites.boolean"    => __("messages.boolean"),
            "get_rated.required"                       => __("messages.required"),
            "get_rated.boolean"                        => __("messages.boolean"),
            "get_a_booking.required"                   => __("messages.required"),
            "get_a_booking.boolean"                    => __("messages.boolean"),
            "receive_notifications.required"           => __("messages.required"),
            "receive_notifications.boolean"            => __("messages.boolean"),
            "incoming_messages_notifications.required" => __("messages.required"),
            "incoming_messages_notifications.boolean"  => __("messages.boolean"),
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function attributes()
    {
        return [
            "my_profile_added_to_favorites"   => __("attributes.all_settings.my_profile_added_to_favorites"),
            "get_rated"                       => __("attributes.all_settings.get_rated"),
            "get_a_booking"                   => __("attributes.all_settings.get_a_booking"),
            "receive_notifications"           => __("attributes.all_settings.receive_notifications"),
            "incoming_messages_notifications" => __("attributes.all_settings.incoming_messages_notifications"),
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
