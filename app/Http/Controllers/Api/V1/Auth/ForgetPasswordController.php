<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\{ForgetPasswordRequest, CodeCheckRequest, ResetPasswordRequest};
use App\Http\Resources\Api\V1\{ErrorResource, SuccessResource};
use App\Models\{Agent, ResetCodePassword};
use Illuminate\Support\Facades\{Log, Mail};
use App\Mail\SendCodeResetPassword;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request): SuccessResource|ErrorResource
    {
        try {
//            $code = random_int(1000, 9999);
            $code = 1234;
            ResetCodePassword::updateOrCreate(['email' => $request->email], ['code' => $code]);
//            Mail::to($request->email)->send(new SendCodeResetPassword($code));
            return SuccessResource::make(__('auth.passwords.code_sent'));
        } catch (\Exception $e) {
            Log::channel($request->userType['type'])->error("Error in ForgetPasswordController@forgetPassword: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('auth.passwords.code_sent_error'), 500);
        }
    }

    public function checkCode(CodeCheckRequest $request): SuccessResource|ErrorResource
    {
        try {
            if ($resetCode = ResetCodePassword::firstWhere('code', $request->code)) {
                return $resetCode->updated_at <= now()->addHour()
                    ? SuccessResource::make(['message' => __('auth.passwords.code_is_valid'), 'code' => $request->code])->withWrappData()
                    : ErrorResource::make(__('auth.passwords.code_is_expire'), 422);
            }
            return ErrorResource::make(__('auth.passwords.code_is_invalid'), 422);
        } catch (\Exception $e) {
            Log::channel($request->userType['type'])->error("Error in ForgetPasswordController@checkCode: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('auth.passwords.code_sent_error'), 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): SuccessResource|ErrorResource
    {
        try {
            if ($passwordReset = ResetCodePassword::firstWhere(['code' => $request->code, 'email' => $request->email])) {
                if ($passwordReset->updated_at <= now()->addHour()) {
                    (new $request->userType['model'])->firstWhere('email', $passwordReset->email)->update(['password' => $request->password]);
                    $passwordReset->delete();
                    return SuccessResource::make(__('auth.passwords.reset_success'));
                }
                $passwordReset->delete();
                return ErrorResource::make(__('auth.passwords.code_is_expire'), 422);
            }
            return ErrorResource::make(__('auth.passwords.code_is_invalid'), 422);
        } catch (\Exception $e) {
            Log::channel($request->userType['type'])->error("Error in ForgetPasswordController@resetPassword: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('auth.passwords.reset_error'), 500);
        }
    }
}
