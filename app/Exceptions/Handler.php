<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    public function report(Throwable $exception) {

        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    public function error($code = 500, $messages = '', $exception) {
        $debug = config('app.debug');
        $response = [
            'status' => false,
            'status_code' => $code,
            'message' => $messages
        ];
        if ($debug) {
            $response['exception'] = get_class($exception);
            $response['trace'] = explode("\n", $exception->getTraceAsString());
        }

        return response()->json($response, $code);
    }

    public function register() {
        $this->renderable(function (ValidationException $e, $request) {
            $validationErrors = $e->validator->errors()->getMessages();
            $validationErrors = array_map(function ($error) {
                $v_error = array_map(function ($message) {
                    return $message;
                }, $error);

                return implode(",", $v_error);
            }, $validationErrors);

            return $this->error(422, $validationErrors, $e);
        });

        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return $this->error(403, 'Unauthorized', $e);
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            return $this->error(401, 'Unauthenticated', $e);
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            return $this->error(404, 'Data tidak ditemukan', $e);
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            return $this->error(404, 'Endpoint tidak ditemukan', $e);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return $this->error(405, 'Metode request tidak diizinkan', $e);
        });

        $this->renderable(function (QueryException $e, $request) {
            return $this->error(500, 'Error Query', $e);
        });

        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }
}
