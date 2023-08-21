<?php

namespace App\Http\Requests\Api\V1\Auth;


use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "email" => ["required", "string", "email", "max:255"],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            "email.required" => __("validation.required", ["attribute" => __("attributes.email")]),
            "email.string"   => __("validation.string", ["attribute" => __("attributes.email")]),
            "email.email"    => __("validation.email", ["attribute" => __("attributes.email")]),
            "email.max"      => __("validation.max.string", ["attribute" => __("attributes.email"), "max" => 255]),
        ];
    }
}
