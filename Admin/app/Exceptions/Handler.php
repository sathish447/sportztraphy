<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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

        if ($exception instanceof TokenMismatchException) {
            // Redirect to a form. Here is an example of how I handle mine
            return redirect()->back()->with('error', "Oops! Seems you couldn't submit form for a long time. Please try again.");
        }

        // 404 page when a model is not found
        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [], 404);
        }

         if ($exception instanceof NotFoundHttpException){

            $response1['result']=Null;
            $response1['message']="Invalid URL";
            $response1['error']="Resource not found";          
            $response1['success']="false";
             return response($response1, 404);
        }

         if ($exception instanceof MethodNotAllowedHttpException) {

            $response1['result']=Null;
            $response1['message']="Invalid URL";
            $response1['error']="Method is not allowed for the requested route";          
            $response1['success']="false";
             return response($response1, 405);     
        }



        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
