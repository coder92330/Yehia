<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !auth()->check();
    }

    public function rules(): array
    {
        return [
            'email'      => ['required', 'string', 'email', "exists:{$this->userType['table']},email"],
            'password'   => ['required', 'string'],
            'device_key' => ['sometimes', 'string']
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'      => __('validation.required', ['attribute' => __('attributes.email')]),
            'email.string'        => __('validation.string', ['attribute' => __('attributes.email')]),
            'email.email'         => __('validation.email', ['attribute' => __('attributes.email')]),
            'email.exists'        => __('validation.exists', ['attribute' => __('attributes.email')]),
            'password.required'   => __('validation.required', ['attribute' => __('attributes.password')]),
            'password.string'     => __('validation.string', ['attribute' => __('attributes.password')]),
            'device_key.string'   => __('validation.string', ['attribute' => __('attributes.device_key')]),
        ];
    }
}
