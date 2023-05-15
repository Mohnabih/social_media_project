<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiCode;
use App\Helpers\HelperUser;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Api\Auth\LoginUserRequest;
use App\Http\Requests\Api\Auth\UpdateFcmTokenRequest;
use App\Models\User;

class AuthController extends AppBaseController
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'login',
        ]]);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \App\Http\Requests\Auth\LoginUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request)
    {
        $input = $request->validated();

        // Verify the entered field if a email or phone number with password.
        $data = HelperUser::credentials($input);
        if ($data) {
            if ($token = auth()->attempt($data)) {
                $user = auth()->user();
                $user->remember_token = $token;
                $user->logouted_at = null;
                $user->save();
                return  $this->sendResponse(
                    [
                        "token" => $token,
                        "user" => User::with('profile')->find($user->id)
                    ],
                    __("User successfully logged in"),
                    ApiCode::SUCCESS,
                    0
                );
            } else {
                // returns the type of the entered filed [email || phone].
                $type = array_keys($data)[0];

                //if authentication is unsuccessfully.
                return $this->sendResponse(
                    null,
                    __('Invalid ') . __($type) . __(' or Password'),
                    ApiCode::BAD_REQUEST,
                    1
                );
            }
        } else {
            return $this->sendResponse(
                null,
                __('Error in entering your phone or email'),
                ApiCode::BAD_REQUEST,
                1
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Auth\UpdateFcmTokenRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update_fcm(UpdateFcmTokenRequest $request)
    {
        $user = auth()->user();
        $input = $request->validated();
        $user->fcm_token = $input['fcm_token'];
        $user->save();
        return $this->sendResponse(
            null,
            'updated fcm_token successfully',
            ApiCode::SUCCESS,
            0
        );
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $user = auth()->user();
        $user->fcm_token = null;
        $user->logouted_at = Carbon::now();
        $user->save();
        auth()->logout();
        return  $this->sendResponse(
            null,
            __("User successfully logged out"),
            ApiCode::SUCCESS,
            0
        );
    }
}
