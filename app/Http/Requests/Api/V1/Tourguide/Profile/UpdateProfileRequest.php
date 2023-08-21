<?php

namespace App\Http\Requests\Api\V1\Tourguide\Profile;

use App\Exceptions\ApiAuthenticationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            "country_id"          => ["sometimes", "exists:countries,id"],
            "company_id"          => ["sometimes", "exists:companies,id"],
            "first_name"          => ["sometimes", "string", "max:255"],
            "last_name"           => ["sometimes", "string", "max:255"],
            "username"            => ["sometimes", "string", "max:255", "unique:agents,username," . auth('agent_api')->id()],
            "email"               => ["sometimes", "string", "email", "max:255", "unique:agents,email," . auth('agent_api')->id()],
            "password"            => ["sometimes", "string", "min:8", "confirmed"],
            "birthdate"           => ["nullable", "date"],
            "education"           => ["nullable", "string", "max:255"],
            "age"                 => ["nullable", "integer"],
            "years_of_experience" => ["nullable", "integer"],
            "facebook"            => ["nullable", "url"],
            "twitter"             => ["nullable", "url"],
            "instagram"           => ["nullable", "url"],
            "linkedin"            => ["nullable", "url"],
            "avatar"              => ["nullable", "image"],
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
            "country_id.exists"           => __('validation.exists'),
            "company_id.exists"           => __('validation.exists'),
            "first_name.string"           => __('validation.string'),
            "first_name.max"              => __('validation.max.string'),
            "last_name.string"            => __('validation.string'),
            "last_name.max"               => __('validation.max.string'),
            "username.string"             => __('validation.string'),
            "username.max"                => __('validation.max.string'),
            "username.unique"             => __('validation.unique'),
            "email.string"                => __('validation.string'),
            "email.email"                 => __('validation.email'),
            "email.max"                   => __('validation.max.string'),
            "email.unique"                => __('validation.unique'),
            "password.string"             => __('validation.string'),
            "password.min"                => __('validation.min.string'),
            "password.confirmed"          => __('validation.confirmed'),
            "birthdate.date"              => __('validation.date'),
            "education.string"            => __('validation.string', ['attribute' => __('attributes.education')]),
            "education.max"               => __('validation.max.string'),
            "age.integer"                 => __('validation.integer'),
            "years_of_experience.integer" => __('validation.integer'),
            "facebook.url"                => __('validation.url'),
            "twitter.url"                 => __('validation.url'),
            "instagram.url"               => __('validation.url'),
            "linkedin.url"                => __('validation.url'),
            "avatar.image"                => __('validation.image'),
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
            "country_id"          => __('attributes.country'),
            "company_id"          => __('attributes.company.name'),
            "first_name"          => __('attributes.first_name'),
            "last_name"           => __('attributes.last_name'),
            "username"            => __('attributes.username'),
            "email"               => __('attributes.email'),
            "password"            => __('attributes.password'),
            "birthdate"           => __('attributes.birthdate'),
            "education"           => __('attributes.education'),
            "age"                 => __('attributes.age'),
            "years_of_experience" => __('attributes.years_of_experience'),
            "facebook"            => __('attributes.facebook'),
            "twitter"             => __('attributes.twitter'),
            "instagram"           => __('attributes.instagram'),
            "linkedin"            => __('attributes.linkedin'),
            "avatar"              => __('attributes.avatar'),
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
