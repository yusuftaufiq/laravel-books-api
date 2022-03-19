<?php

namespace App\Exceptions;

use App\Support\HttpApiFormat;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Validation\ValidationException;
use Phpro\ApiProblem\Http\NotFoundProblem;
use Phpro\ApiProblem\Http\UnauthorizedProblem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function handleApiNotFoundHttpException(NotFoundHttpException $e, Request $request): HttpResponse
    {
        $message = $e->getMessage() ?: 'Page not found';
        $notFoundProblem = new NotFoundProblem($message);

        return response($notFoundProblem->toArray(), $e->getStatusCode());
    }

    public function handleApiTooManyRequestsHttpException(TooManyRequestsHttpException $e, Request $request): HttpResponse
    {
        $retryAfter = $e->getHeaders()['Retry-After'] ?? null;
        $tooManyRequestsProblem = new HttpApiFormat($e->getStatusCode(), [
            'detail' => "You have exceeded the rate limit. Please try again in {$retryAfter} seconds.",
        ]);

        return response($tooManyRequestsProblem->toArray());
    }

    public function handleApiAuthenticationException(AuthenticationException $e, Request $request): HttpResponse
    {
        $message = $e->getMessage() ?: 'You are not authorized to perform this action.';
        $unauthorizedProblem = new UnauthorizedProblem($message);

        return response($unauthorizedProblem->toArray(), Response::HTTP_UNAUTHORIZED);
    }

    public function handleApiValidationException(ValidationException $e, Request $request): HttpResponse
    {
        $message = $e->getMessage() ?: 'The request could not be processed.';
        $unprocessableEntityProblem = new HttpApiFormat(Response::HTTP_UNPROCESSABLE_ENTITY, [
            'detail' => $message,
        ]);

        return response($unprocessableEntityProblem->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return $this->handleApiNotFoundHttpException($e, $request);
            }
        });

        $this->renderable(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return $this->handleApiTooManyRequestsHttpException($e, $request);
            }
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return $this->handleApiAuthenticationException($e, $request);
            }
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return $this->handleApiValidationException($e, $request);
            }
        });
    }
}
