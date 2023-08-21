<?php

namespace App\Http\Requests\Api\V1\Agent\Message;

use App\Exceptions\ApiAuthenticationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
            'message'   => ['required', 'string'],
            'chat_with' => ['required', 'in:tourguide,user'],
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
            'message.required'   => __('validation.required'),
            'message.string'     => __('validation.string'),
            'chat_with.required' => __('validation.required'),
            'chat_with.in'       => __('validation.in'),
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
            'message'   => __('attributes.message'),
            'chat_with' => __('attributes.chat_with'),
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
