<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Resources\Api\V1\ErrorResource;
use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
            'code' => ['required', 'string', 'size:4'],
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
            'code.required' => __('validation.required', ['attribute' => __('attributes.code')]),
            'code.string'   => __('validation.string', ['attribute' => __('attributes.code')]),
            'code.size'     => __('validation.size.numeric', ['attribute' => __('attributes.code'), 'size' => 4]),
        ];
    }

    /**
     * Handle a failed authorization attempt.
     * @return ErrorResource
     */
    protected function failedAuthorization()
    {
        return ErrorResource::make(__('auth.email_already_verified'), 422);
    }
}
