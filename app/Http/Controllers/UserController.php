<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * create a user account.
     *
     * @param  RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $this->userService->registerUser($request->validated());

        return response()->json([
            'message' => 'Successfully Created user',
        ], 201);
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

        $token = $this->userService->loginUser($credentials);

        return response()->json(['token' => $token], 200);
    }

    /**
     * get user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json(['user' => $user], 200);
    }

    /**
     * invalidate user's token.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $this->userService->logoutUser();
        return response()->json(
            ['message' => 'Successfully logged out']);
    }

    /**
     * gives a new token and makes the old one invalid.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $newToken = $this->userService->refreshUserToken();
        return response()->json(['token' => $newToken], 200);
    }
}
