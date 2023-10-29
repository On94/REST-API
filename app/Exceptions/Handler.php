<?php

namespace App\Exceptions;

use App\Http\Resources\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param $request
     * @param Throwable $e
     * @return Response|JsonResponse|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse|\Symfony\Component\HttpFoundation\Response
    {
        if ($e instanceof ValidationException) {
            $errors = $e->validator->errors();
            $status = 'error';
            $message = $e->getMessage();
            $statusCode = 422;
        } else {
            $status = 'error';
            $message = $e->getMessage();
            $statusCode = 500;
        }
        return ApiResponse::make([
            'status' => $status,
            'message' => $message,
            'data' => $errors ?? null,
        ])->response()->setStatusCode($statusCode);
    }
}
