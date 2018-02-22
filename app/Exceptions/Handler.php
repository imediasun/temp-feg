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

        //empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        $errorMessage = '<h1>Whoops, looks like something went wrong.</h1>
1/1 ErrorException in ' . $e->getFile() . ' line ' . $e->getLine() . ': ' . $e->getMessage() . ': ' . $e->getTraceAsString() . "<br />";

        $TempErrorFile = fopen(storage_path() . "\logs\TempErrorFile.txt", "w");

        fwrite($TempErrorFile, $errorMessage);
        fclose($TempErrorFile);
        //echo nl2br($e->getMessage());
        //  echo nl2br($e->getTraceAsString());
        // die;

        //   if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        return response()->view('errors.404', [], 500);
        //  }

        return parent::render($request, $e);
    }
}
