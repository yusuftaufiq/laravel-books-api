<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

/**
 * @property User $user
 */
final class RegisterController extends Controller
{
    final public function __construct(
        private UserInterface $user,
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \App\Http\Resources\UserResource
     */
    final public function __invoke(RegisterRequest $request)
    {
        $user = $this->user->create($request->validated());

        return new UserResource($user);
    }
}
