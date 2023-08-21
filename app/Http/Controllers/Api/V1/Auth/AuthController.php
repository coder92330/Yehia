<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\{Agent\AgentResource, ErrorResource, SuccessResource, Tourguide\TourguideResource};
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request): SuccessResource|ErrorResource
    {
        try {
            if (auth($request->userType['type'])->attempt($request->safe()->all())) {
                DB::beginTransaction();
                $token = auth($request->userType['type'])->user()->createToken('token')->plainTextToken;
                auth($request->userType['type'])->user()->update([
                    'is_active' => true,
                    'is_online' => true,
                    'last_active' => now(),
                ]);
                DB::commit();
                return SuccessResource::make([
                    'message' => __('auth.login_success'),
                    'token' => $token,
                    'user' => ($request->userType['resource'])::make(auth($request->userType['type'])->user()->load($request->userType['relations'])),
                ])->withWrappData();
            }
            return ErrorResource::make(__('auth.invalid_credentials'), 401);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::channel($request->userType['type'])->error("Error in AuthController@login: {$exception->getMessage()} at Line: {$exception->getLine()} in File: {$exception->getFile()}");
            return ErrorResource::make($exception->getMessage(), 500);
        }
    }

    public function logout(Request $request): SuccessResource|ErrorResource
    {
        try {
            DB::beginTransaction();
            auth($request->userType['guard'])->user()->update([
                'is_active' => false,
                'is_online' => false,
                'last_active' => now(),
            ]);
            auth($request->userType['guard'])->user()->currentAccessToken()->delete();
            DB::commit();
            return SuccessResource::make(__('auth.logout_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel($request->userType['type'])->error("Error in AuthController@logout: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return ErrorResource::make(__('auth.logout_error'), 500);
        }
    }

    public function checkToken(Request $request): SuccessResource|ErrorResource
    {
        return auth($request->userType['guard'])->user()->currentAccessToken()->token === $request->token
            ? SuccessResource::make(__('auth.token_valid'))
            : ErrorResource::make(__('auth.token_expired'), 401);
    }
}
