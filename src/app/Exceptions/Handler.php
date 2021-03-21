<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Debug\Exception\FlattenException;
use Monolog\Logger;
use Monolog\Handler\SlackWebhookHandler;
use App\Libs\Errors;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
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
        $fe = FlattenException::create($exception);
        $statusCode = $fe->getStatusCode();

        //$monologger = new Logger('slack');
        //$slackHandler = new SlackWebhookHandler(env('LOG_SLACK_WEBHOOK_URL'),env('LOG_CHANNEL'),'Monolog');
        //$monologger->pushHandler($slackHandler);
        //dd(Errors::getLogLevel($statusCode));
        //$monologger->addRecord(Errors::getLogLevel($statusCode), "status {$statusCode} at {$exception->getFile()} error {$exception->getMessage()}");
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
