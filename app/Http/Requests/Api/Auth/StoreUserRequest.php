<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\BaseRequest;

class StoreUserRequest extends BaseRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'gender' => 'required|in:0,1',
            'email' => 'required_without:phone|email:filter|max:255|unique:users',
            'phone' => 'required_without:email|regex:/^([+]+[1-9\s\-\+\(\)]*)$/|min:9|max:15|unique:users,phone',
            'password' => 'required|string|min:8|confirmed'
        ];
    }
}
