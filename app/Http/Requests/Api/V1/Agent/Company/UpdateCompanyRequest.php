<?php

namespace App\Http\Requests\Api\V1\Agent\Company;

use App\Exceptions\ApiAuthenticationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name'        => ['sometimes', 'string'],
            'email'       => ['sometimes', 'email', 'unique:companies,email'],
            'website'     => ['sometimes', 'string'],
            'address'     => ['sometimes', 'string'],
            'specialties' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'facebook'    => ['sometimes', 'string'],
            'twitter'     => ['sometimes', 'string'],
            'instagram'   => ['sometimes', 'string'],
            'linkedin'    => ['sometimes', 'string'],
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
            'name.string'        => __('validation.string'),
            'email.email'        => __('validation.email'),
            'email.unique'       => __('validation.unique'),
            'website.string'     => __('validation.string'),
            'address.string'     => __('validation.string'),
            'specialties.string' => __('validation.string'),
            'description.string' => __('validation.string'),
            'facebook.string'    => __('validation.string'),
            'twitter.string'     => __('validation.string'),
            'instagram.string'   => __('validation.string'),
            'linkedin.string'    => __('validation.string'),
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
            'name'        => __('attributes.name'),
            'email'       => __('attributes.email'),
            'website'     => __('attributes.website'),
            'address'     => __('attributes.address'),
            'specialties' => __('attributes.specialties'),
            'description' => __('attributes.description'),
            'facebook'    => __('attributes.facebook'),
            'twitter'     => __('attributes.twitter'),
            'instagram'   => __('attributes.instagram'),
            'linkedin'    => __('attributes.linkedin'),
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
