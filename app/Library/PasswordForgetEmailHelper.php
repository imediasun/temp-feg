<?php
/**
 * Created by PhpStorm.
 * User: adnan
 * Date: 11/1/2016
 * Time: 11:00 AM
 */

namespace App\Library;
use Password;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;


class PasswordForgetEmailHelper {
    public static function notify(Request $request)
    {
        dump( Password::sendResetLink(
            ['email' => 'adnanali199@gmail.com']),
            function($message){
                return $message->subject('Your Account Password');
            }
        );
    }
}