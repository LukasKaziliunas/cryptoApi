<?php

namespace App\Exceptions;

use ErrorException;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use TypeError;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (TokenInvalidException $e, $request) {
            return response()->json(['message' => 'Invalid token'], 401);
        });

        $this->renderable(function (TokenExpiredException $e, $request) {
            return response()->json(['message' => 'Token has Expired'], 401);
        });

        $this->renderable(function (JWTException $e, $request) {
            return response()->json(['message' => 'Jwt error'], 500);
        });

        $this->renderable(function (JwtTokenNotParsedException $e, $request) {
            return response()->json(['message' => $e->getMessage()], 401);
        });

        $this->renderable(function (JwtInvalidCredentialsException $e, $request) {
            return response()->json(['message' => $e->getMessage()], 401);
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            return response()->json(['message' => 'User does not exist aaaa.'], 401);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Object not found'], 404);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Method not allowed'], 405);
            }
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'This action is unauthorized'], 403);
            }
        });

        $this->renderable(function (ErrorException $e, $request) {
            Log::debug($e->getMessage());
            return response()->json(['message' => 'Internal error'], 500);
        });

        $this->renderable(function (ClientException $e, $request) {
            Log::channel('myErrors')->error($e->getMessage());
            return response()->json(['message' => 'Bad request to external api'], 500);
        });

        $this->renderable(function (ConnectException $e, $request) {
            Log::channel('myErrors')->error($e->getMessage());
            return response()->json(['message' => 'Could not connect to external api'], 500);
        });

        $this->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'errors' => $e->getErrors(),
                'fields' => $e->getFields(),
            ], 422);
        });

        $this->renderable(function (Exception $e, $request) {
            Log::channel('myErrors')->error($e->getMessage());
            return response()->json(['message' => 'Internal error'], 500);
        });

        $this->renderable(function (TypeError $e, $request) {
            Log::channel('myErrors')->error($e->getMessage());
            return response()->json(['message' => 'Internal error'], 500);
        });
    }
}
