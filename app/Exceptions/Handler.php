<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use League\OAuth2\Server\Exception\OAuthServerException;
use Illuminate\Auth\AuthenticationException;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $responseConstants = config('constants.RESPONSE_CONSTANTS');

        if ($exception instanceof OAuthServerException) {
            return response()->json(['status' => $responseConstants['STATUS_ERROR'] ,'message' => 'Auth Server Error found, Kindly report it to Administration!', 'type' => 'OAuthServerException' ,'response_code' => 500]);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(['status' => $responseConstants['STATUS_ERROR'] ,'message' => 'Token is invalid', 'type' => 'InvalidToken' ,'response_code' => 403]);
        }

        return parent::render($request, $exception);
    }
}
