<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
     /**
     * create a user account.
     *
     * @param  RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = new User([
            'name'=> $request->input('name'),
            'lastname'=> $request->input('lastname'),
            'email'=> $request->input('email'),
            'password'=> bcrypt($request->input('password'))
        ]);

        $user->save();

        return response()->json([
            'message'=>'Successfully Created user'
        ],201);
    }

     /**
     * log the user in and give auth token.
     *
     * @param  LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Invalid Credentials'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        }
        return response()->json([
            'token' => $token
        ], 200);
    }

     /**
     * get user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser(){
        $user = auth('api')->user();
        return response()->json(['user'=>$user], 200);
    }

     /**
     * invalidate user's token.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(){
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

     /**
     * gives a new token and makes the old one invalid.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        return response()->json([
            'token' => auth()->refresh()
        ], 200);
    }
}
