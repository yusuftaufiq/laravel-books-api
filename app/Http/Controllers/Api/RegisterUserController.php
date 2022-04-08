<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property User $user
 */
final class RegisterUserController extends Controller
{
    public function __construct(
        private UserInterface $user,
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\RegisterUserRequest  $request
     *
     * @return \App\Http\Resources\UserResource
     */
    public function __invoke(RegisterUserRequest $request): UserResource
    {
        /** @var array $validated */
        $validated = $request->validated();

        /** @phpstan-ignore-next-line */
        $user = $this->user->create($validated);

        $userResource = new UserResource($user);
        $userResource->withResponse($request, new JsonResponse(status: Response::HTTP_CREATED));

        return $userResource;
    }
}
