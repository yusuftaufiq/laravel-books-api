<?php

namespace App\Exceptions;

use App\Support\HttpApiFormat;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
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

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable($this->handleNotFoundHttpException(...));
        $this->renderable($this->handleTooManyRequestsHttpException(...));
        $this->renderable($this->handleAuthenticationException(...));
        $this->renderable($this->handleValidationException(...));
    }

    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|null
     */
    protected function handleNotFoundHttpException(NotFoundHttpException $e, Request $request)
    {
        if ($request->is('api/*')) {
            $message = $e->getMessage();
            $notFoundProblem = new NotFoundProblem($message);

            return response($notFoundProblem->toArray(), $e->getStatusCode());
        }
    }

    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|null
     */
    protected function handleTooManyRequestsHttpException(TooManyRequestsHttpException $e, Request $request)
    {
        if ($request->is('api/*')) {
            $retryAfter = $e->getHeaders()['Retry-After'];
            $tooManyRequestsProblem = new HttpApiFormat($e->getStatusCode(), [
                'detail' => "You have exceeded the rate limit. Please try again in {$retryAfter} seconds.",
            ]);

            return response($tooManyRequestsProblem->toArray(), Response::HTTP_TOO_MANY_REQUESTS);
        }
    }

    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|null
     */
    protected function handleAuthenticationException(AuthenticationException $e, Request $request)
    {
        if ($request->is('api/*')) {
            $message = $e->getMessage();
            $unauthorizedProblem = new UnauthorizedProblem($message);

            return response($unauthorizedProblem->toArray(), Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory|null
     */
    protected function handleValidationException(ValidationException $e, Request $request)
    {
        if ($request->is('api/*')) {
            $message = $e->getMessage();
            $unprocessableEntityProblem = new HttpApiFormat(Response::HTTP_UNPROCESSABLE_ENTITY, [
                'detail' => $message,
            ]);

            return response($unprocessableEntityProblem->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
