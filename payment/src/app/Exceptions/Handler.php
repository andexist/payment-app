<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Class Handler
 * @package App\Exceptions
 */
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
     * @param Exception $exception
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse|Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json(["message" => $exception->errors()])
                ->setStatusCode(HTTPResponse::HTTP_BAD_REQUEST);
        } else if ($exception instanceof ApiException) {
            return response()->json(["message" => $exception->getMessage()])
                ->setStatusCode($exception->getCode());
        } else if ($exception instanceof FatalThrowableError) {
            return response()->json(["message" => $exception->getMessage()])
                ->setStatusCode($exception->getCode());
        }
    }
}
