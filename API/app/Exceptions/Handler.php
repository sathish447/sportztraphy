<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // detect instance
        if ($exception instanceof UnauthorizedHttpException) {
            // detect previous instance
            if ($exception->getPrevious() instanceof TokenExpiredException) {
                
                return response()->json(['status' => false, 'response' => null, 'message' => 'Token Expired']);
            } else if ($exception->getPrevious() instanceof TokenInvalidException) {
                
                return response()->json(['status' => false, 'response' => null, 'message' => 'Invalid Token']);
            } else if ($exception->getPrevious() instanceof TokenBlacklistedException) {
                
                return response()->json(['status' => false, 'response' => null, 'message' => 'Token Blocklisted']);
            } else {
                return response()->json(['status' => false, 'response' => null, 'message' => 'Unauthorized Request']);
            }
        }

        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                return response()->json(['status' => false, 'response' => null, 'message' => 'URL Not Found']);
            }
            
            if ($exception->getStatusCode() == 500) {
                return response()->json(['status' => false, 'response' => null, 'message' => 'Oops! Something went wrong']);
            }

            if ($exception->getStatusCode() == 405) {
                return response()->json(['status' => false, 'response' => null, 'message' => 'Method is not allowed for the requested route']);
            }

            
        }

        return parent::render($request, $exception);
    }
}
