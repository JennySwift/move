<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
//        dd($exception);
        if ($exception instanceof ModelNotFoundException) {
            $model = (new \ReflectionClass($exception->getModel()))->getShortName();

            return response([
                'error' => "{$model} not found.",
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        else if ($exception instanceof ValidationException) {
            return response([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        else if ($exception instanceof \InvalidArgumentException) {
            return response([
                'error' => $exception->getMessage(),
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        else if ($exception instanceof HttpResponseException) {
            if ($exception->getResponse()->getStatusCode() === Response::HTTP_FORBIDDEN) {
                return response([
                    'error' => 'Forbidden',
                    'status' => Response::HTTP_FORBIDDEN
                ], Response::HTTP_FORBIDDEN);
            }
        }

        else if ($exception instanceof NotFoundHttpException) {
            return response([
                'error' => 'Not found',
                'status' => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }

        else if ($exception instanceof AuthenticationException) {
            return parent::render($request, $exception);
        }

        else if ($exception instanceof Exception) {
            if (!method_exists($exception, 'getResponse') || !$exception->getResponse()->getContent()) {
                if ($exception->getCode() > 0) {
                    return response([
                        'error' => $exception->getMessage(),
                        'status' => $exception->getCode()
                    ], $exception->getCode());
                }
            }
            return response ([
                'error' => $exception->getMessage()
            ]);
        }

        else {
            return response([
                'error' => 'There was an error'
            ], 422);
        }

//        if ($e instanceof GeneralException) {
//            return response([
//                'error' => $e->errorMessage,
//                'status' => Response::HTTP_BAD_REQUEST
//            ], Response::HTTP_BAD_REQUEST);
//        }

        return parent::render($request, $exception);
    }
}
