<?php

namespace App\Helpers;

class HelperUser
{
    public static function codeGenerator()
    {
        //return ex:333333;
        return mt_rand(111111, 999999);
    }

    /**
     * Verify the entered field if a email or phone number.
     *
     * @param  array $data
     * @return array $data
     */
    public static function credentials($data)
    {
        if (is_numeric($data['email_or_phone'])) {
            return ['phone' => $data['email_or_phone'], 'password' => $data['password']];
        } elseif (filter_var($data['email_or_phone'], FILTER_VALIDATE_EMAIL)) {
            return ['email' => $data['email_or_phone'], 'password' => $data['password']];
        }
    }
}
