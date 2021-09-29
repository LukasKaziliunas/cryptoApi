<?php

namespace App\Services;

use App\Exceptions\JwtInvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{

    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

     /**
     * create user's account.
     *
     * @param array valid inputs
     * @return User
     */
    public function registerUser($validatedInputs)
    {
        $user = new User([
            'name' => $validatedInputs['name'],
            'lastname' => $validatedInputs['lastname'],
            'email' => $validatedInputs['email'],
            'password' => bcrypt($validatedInputs['password']),
        ]);

        $user->save();

        return $user;
    }

    /**
     * login user
     * @param array email and password
     * @return string auth token
     */
    public function loginUser($credentials)
    {
        if (!$token = $this->auth::attempt($credentials)) {
            throw new JwtInvalidCredentialsException();
        }

        return $token;
    }

    /**
     * logout user
     */
    public function logoutUser()
    {
        $this->auth::logout();
    }

    /**
     * make a new auth token
     * @return string auth token
     */
    public function refreshUserToken()
    {
       return $this->auth::refresh();
    }
}