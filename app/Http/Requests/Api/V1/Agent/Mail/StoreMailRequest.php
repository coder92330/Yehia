<?php

namespace App\Http\Requests\Api\V1\Agent\Mail;

use App\Exceptions\ApiAuthenticationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreMailRequest extends FormRequest
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
            'subject'     => ['required'],
            'from'        => ['required', 'email'],
            'to'          => ['required', 'email'],
            'body'        => ['required'],
            'cc'          => ['nullable', 'email'],
            'bcc'         => ['nullable', 'email'],
            'reply_to'    => ['nullable', 'email'],
            'attachments' => ['nullable'],
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
            'subject.required' => __('validation.required'),
            'from.required'    => __('validation.required'),
            'from.email'       => __('validation.email'),
            'to.required'      => __('validation.required'),
            'to.email'         => __('validation.email'),
            'body.required'    => __('validation.required'),
            'cc.email'         => __('validation.email'),
            'bcc.email'        => __('validation.email'),
            'reply_to.email'   => __('validation.email'),
        ];
    }

    public function attributes()
    {
        return [
            'subject'  => __('attributes.subject'),
            'from'     => __('attributes.from'),
            'to'       => __('attributes.to'),
            'body'     => __('attributes.body'),
            'cc'       => __('attributes.cc'),
            'bcc'      => __('attributes.bcc'),
            'reply_to' => __('attributes.reply_to'),
            'attachments' => __('attributes.attachments'),
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
