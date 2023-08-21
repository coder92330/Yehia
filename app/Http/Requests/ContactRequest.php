<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email'],
            'phone'      => ['required', 'string'],
            'message'    => ['required', 'string'],
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
            'first_name.required' => 'First name is required',
            'first_name.string'   => 'First name must be a string',
            'first_name.max'      => 'First name must be less than 100 characters',
            'last_name.required'  => 'Last name is required',
            'last_name.string'    => 'Last name must be a string',
            'last_name.max'       => 'Last name must be less than 100 characters',
            'email.required'      => 'Email is required',
            'email.email'         => 'Email must be a valid email address',
            'phone.required'      => 'Phone is required',
            'phone.string'        => 'Phone must be a string',
            'message.required'    => 'Message is required',
            'message.string'      => 'Message must be a string',
            'message.max'         => 'Message must be less than 1000 characters',
        ];
    }
}
