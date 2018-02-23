<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {

        if (config("app.SHOW_EXCEPTION") == true) {
        $errorMessage = '<h1>Whoops, looks like something went wrong.</h1>
<h1>1/1 ErrorException in ' . basename($e->getFile()) . ' line ' . $e->getLine() . ': ' . $e->getMessage() . ' </h1><br /> ' . str_replace("#", "<br />", $e->getTraceAsString()) . "<br />";

        $errorMessage = "<div id='ActualError' style='display: none;'>" . $errorMessage . "</div>";

        //   if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        return response()->view('errors.404', ['errorMessage' => $errorMessage], 500);
        //  }
        } else {
            return parent::render($request, $e);
        }


    }
}
