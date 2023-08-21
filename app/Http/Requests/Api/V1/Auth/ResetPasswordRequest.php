<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            "code"     => ["required", "string", "max:4", "exists:reset_code_passwords,code"],
            "email"    => ["required", "string", "email", "exists:{$this->userType['table']},email"],
            "password" => ["required", "string", "min:8", "confirmed"],
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
            "code.required"     => __("validation.required", ["attribute" => __("attributes.code")]),
            "code.string"       => __("validation.string", ["attribute" => __("attributes.code")]),
            "code.max"          => __("validation.max.string", ["attribute" => __("attributes.code"), "max" => 4]),
            "code.exists"       => __("validation.exists", ["attribute" => __("attributes.code")]),
            "email.required"    => __("validation.required", ["attribute" => __("attributes.email")]),
            "email.string"      => __("validation.string", ["attribute" => __("attributes.email")]),
            "email.email"       => __("validation.email", ["attribute" => __("attributes.email")]),
            "email.exists"      => __("validation.exists", ["attribute" => __("attributes.email")]),
            "password.required" => __("validation.required", ["attribute" => __("attributes.password")]),
            "password.string"   => __("validation.string", ["attribute" => __("attributes.password")]),
            "password.min"      => __("validation.min.string", ["attribute" => __("attributes.password"), "min" => 8]),
            "password.confirmed"=> __("validation.confirmed", ["attribute" => __("attributes.password")]),
        ];
    }
}
