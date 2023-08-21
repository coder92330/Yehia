<?php

namespace App\Http\Requests\Api\V1\Tourguide\Appointment;

use App\Exceptions\ApiAuthenticationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
            'start_at' => ['required', 'date_format:Y-m-d H:i:s'],
            'end_at'   => ['required', 'date_format:Y-m-d H:i:s', 'after:start_at'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'start_at.required'    => __('validation.required'),
            'start_at.date_format' => __('validation.date_format', ['format' => 'Y-m-d H:i:s']),
            'end_at.required'      => __('validation.required'),
            'end_at.date_format'   => __('validation.date_format', ['format' => 'Y-m-d H:i:s']),
            'end_at.after'         => __('validation.after', ['other' => __('attributes.start_at')]),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, mixed>
     */
    public function attributes()
    {
        return [
            'start_at' => __('attributes.start_at'),
            'end_at'   => __('attributes.end_at'),
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
