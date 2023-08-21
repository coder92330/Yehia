<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Mail\SendVerificationCode;
use App\Http\Requests\Api\V1\Auth\{GenerateOtpRequest, VerifyOtpRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Mail, DB, Log};
use App\Http\Resources\Api\V1\{SuccessResource, ErrorResource};
use App\Http\Controllers\Controller;

class EmailVerifcationController extends Controller
{
    public function generateOtp(Request $request)
    {
        try {
            DB::beginTransaction();
            $code = random_int(1000, 9999);
            auth($request->userType['guard'])->user()->otp()->updateOrCreate(
                ['otpable_id' => auth($request->userType['guard'])->id(), 'otpable_type' => $request->userType['model']], ['code' => $code]);
            Mail::to(auth($request->userType['guard'])->user()->email)->send(new SendVerificationCode($code));
            DB::commit();
            return SuccessResource::make(__('auth.verification.code_sent'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel($request->userType['type'])->error("Error in EmailVerifcationController@generateOtp: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('auth.verification.code_sent_error'), 500);
        }
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        try {
            if ($verficationCode = auth($request->userType['guard'])->user()->otp()->firstWhere('code', $request->code)) {
                if ($verficationCode->updated_at <= now()->addHour()) {
                    DB::beginTransaction();
                    $verficationCode->delete();
                    auth($request->userType['guard'])->user()->update(['email_verified_at' => now()]);
                    DB::commit();
                    return SuccessResource::make(__('auth.verification.verify_success'));
                }
                return ErrorResource::make(__('auth.verification.code_is_expire'), 422);
            }
            return ErrorResource::make(__('auth.verification.code_is_invalid'), 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel($request->userType['type'])->error("Error in EmailVerifcationController@verifyOtp: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('auth.verification.verify_error'), 500);
        }

    }
}
