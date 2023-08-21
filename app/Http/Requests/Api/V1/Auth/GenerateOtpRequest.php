<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GenerateOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !auth()->user()->verified;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', "exists:{$this->userType['table']},email"],
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
            'email.required' => __('validation.required', ['attribute' => __('attributes.email')]),
            'email.email'    => __('validation.email', ['attribute' => __('attributes.email')]),
            'email.exists'   => __('validation.exists', ['attribute' => __('attributes.email')]),
        ];
    }
}
