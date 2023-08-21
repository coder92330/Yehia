<?php

namespace App\Http\Requests\Api\V1\Agent\Event;

use App\Exceptions\ApiAuthenticationException;
use App\Http\Resources\Api\V1\ErrorResource;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\NoReturn;
use Spatie\Permission\Models\Role;

class StoreEventRequest extends FormRequest
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
        $rules = [
            "city_id"                       => ["required", "exists:cities,id"],
            "agent_id"                      => ["exists:agents,id", Rule::requiredIf(fn() => auth('agent_api')->user()->hasAnyRole(Role::whereIn('name', ['super_admin', 'admin'])->where('guard_name', 'agent')->pluck('name')->toArray()))],
            "name"                          => ["required", "string", "max:255"],
            "description"                   => ["nullable", "string"],
            "lat"                           => ["nullable", "string", "max:255"],
            "lng"                           => ["nullable", "string", "max:255"],
            "days_type"                     => ["sometimes", "in:multi,half,full"],
            "start_at"                      => ["required", "date"],
            "end_at"                        => ["required_if:days_type,multi", "date"],
            "cover"                         => ["nullable", "image"],
            "days"                          => ["required_with:days_type", "array"],
            "days.*.description"            => ["nullable", "string"],
            "days.*.sessions"               => ["sometimes", "array"],
            "days.*.sessions.*.start_at"    => ["required_with:days", "date_format:H:i:s"],
            "days.*.sessions.*.end_at"      => ["required_with:days", "date_format:H:i:s"],
            "days.*.sessions.*.city_id"     => ["required_with:days", "exists:cities,id"],
            "days.*.sessions.*.description" => ["nullable", "string"],
        ];

        if ($this->days_type === 'multi') {
            $rules['days.*.start_at'] = ["required_with:days", "date", "after_or_equal:start_at", "before:end_at"];
            $rules['days.*.end_at']   = ["required_with:days", "date", "after_or_equal:days.*.start_at", "before_or_equal:end_at"];
        } else {
            $rules['days.*.start_time'] = ["required_with:days", "date_format:H:i:s"];
            $rules['days.*.end_time']   = ["required_with:days", "date_format:H:i:s"];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            "city_id.required"              => __('validation.required'),
            "city_id.exists"                => __('validation.exists'),
            "agent_id.required"             => __('validation.required'),
            "agent_id.exists"               => __('validation.exists'),
            "name.required"                 => __('validation.required'),
            "name.string"                   => __('validation.string'),
            "name.max"                      => __('validation.max.string'),
            "description.string"            => __('validation.string'),
            "description.max"               => __('validation.max.string'),
            "lat.string"                    => __('validation.string'),
            "lat.max"                       => __('validation.max.string'),
            "lng.string"                    => __('validation.string'),
            "lng.max"                       => __('validation.max.string'),
            "start_at.required"             => __('validation.required'),
            "start_at.date"                 => __('validation.date'),
            "end_at.required"               => __('validation.required'),
            "end_at.date"                   => __('validation.date'),
            "cover.image"                   => __('validation.image'),
            "days.array"                    => __('validation.array'),
            "days.*.start_at.required_with" => __('validation.required_with', ['values' => __('attributes.days')]),
            "days.*.start_at.date"          => __('validation.date'),
            "days.*.end_at.required_with"   => __('validation.required_with', ['values' => __('attributes.days')]),
            "days.*.end_at.date"            => __('validation.date'),
            "days.*.description.string"     => __('validation.string'),
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
            "city_id"            => __('attributes.city'),
            "agent_id"           => __('attributes.agent'),
            "name"               => __('attributes.name'),
            "description"        => __('attributes.description'),
            "lat"                => __('attributes.lat'),
            "lng"                => __('attributes.lng'),
            "start_at"           => __('attributes.start_at'),
            "end_at"             => __('attributes.end_at'),
            "cover"              => __('attributes.cover'),
            "days"               => __('attributes.days'),
            "days.*.start_at"    => __('attributes.start_at'),
            "days.*.end_at"      => __('attributes.end_at'),
            "days.*.description" => __('attributes.description'),
        ];
    }

    /**
     * @throws ApiAuthenticationException
     */
    protected function failedAuthorization()
    {
        throw new ApiAuthenticationException(__('auth.user_not_authorized'), 403);
    }

    #[NoReturn] protected function passedValidation(): void
    {
        if ($this->days_type === 'multi') {
            $this->merge([
                'days' => collect($this->days)->map(function ($day) {
                    return [
                        'start_at'    => $day['start_at'],
                        'end_at'      => $day['end_at'],
                        'type'        => $this->days_type,
                        'description' => $day['description'] ?? null,
                        'sessions'    => $day['sessions'] ?? null,
                    ];
                })->toArray(),
            ]);
        } else {
            $this->merge([
                'days' => collect($this->days)->map(function ($day) {
                    return [
                        'start_at'    => Carbon::parse($this->start_at)->format('Y-m-d') . ' ' . $day['start_time'],
                        'end_at'      => Carbon::parse($this->start_at)->format('Y-m-d') . ' ' . $day['end_time'],
                        'type'        => $this->days_type,
                        'description' => $day['description'] ?? null,
                        'sessions'    => $day['sessions'] ?? null,
                    ];
                })->toArray(),
            ]);
        }
    }
}
