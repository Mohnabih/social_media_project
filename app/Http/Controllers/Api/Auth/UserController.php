<?php

namespace App\Http\Controllers\Api\Auth;

use App\ApiCode;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Api\Auth\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends AppBaseController
{
     /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except([
            'store'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Auth\StoreUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $inputToUser = $request->only(['email', 'phone', 'password']);
        $user = User::create($inputToUser);

        //create profile
        $inputToProfile = $request->only(['first_name', 'middle_name', 'last_name', 'gender']);
        $inputToProfile['user_id'] = $user->id;
        $user->profile()->create($inputToProfile);

        $token = auth()->login($user);
        $user->remember_token = $token;
        $user->logouted_at = null;
        $user->save();

        return $this->sendResponse(
            [
                "token" => $token,
                "user" => User::with('profile')->find($user->id)
            ],
            __('user created successfully'),
            ApiCode::CREATED,
            0
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($user = User::where("$request->key", $request->value)->first()) {
            if ($user->id == auth()->user()->id) {
                $user->receivedNotifications()->detach();
                $user->settings()->detach();
                $user->delete();
                return $this->sendResponse(
                    null,
                    'deleted successfully',
                    ApiCode::SUCCESS,
                    0
                );
            } else {
                return $this->sendResponse(
                    null,
                    'Dont have permision!',
                    ApiCode::UNAUTHORIZED,
                    1
                );
            }
        } else {
            return $this->sendResponse(
                null,
                'Not found',
                ApiCode::NOT_FOUND,
                1
            );
        }
    }
}
