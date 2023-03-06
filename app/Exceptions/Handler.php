<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e): void {
        });
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->hasHeader('Accept-Language')
                && in_array($request->header('Accept-Language'), config('localization.supported_locales'), true)) {
                \App::setLocale($request->header('Accept-Language'));
            }
            if ($request->is('api/v1/users/*')) {
                return response()->json([
                    'message' => __('User record not found.'),
                ], 404);
            }
        });
    }
}
