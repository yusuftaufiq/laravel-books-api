<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

/**
 * @property User $user
 */
final class RegisterUserController extends Controller
{
    final public function __construct(
        private UserInterface $user,
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\RegisterUserRequest  $request
     * @return \App\Http\Resources\UserResource
     */
    final public function __invoke(RegisterUserRequest $request)
    {
        $user = $this->user->create($request->validated());

        return new UserResource($user);
    }
}
