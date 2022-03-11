<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\PersonalAccessTokenResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @property User $user
 */
final class AuthController extends Controller
{
    final public function __construct(
        private UserInterface $user,
    ) {
    }

    /**
     * @param \App\Http\Requests\LoginRequest $request
     */
    final public function login(LoginRequest $request)
    {
        $user = $this->user->whereEmail($request->get('email'))->firstOrFail();

        if (
            $user instanceof User === false
            || \Hash::check($request->get('password'), $user->password) === false
        ) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $token = $user->createToken($request->get('token_name'), $request->get('expired_at'));

        return (new PersonalAccessTokenResource($token->accessToken))->additional([
            'token' => [
                'type' => 'Bearer',
                'plain_text' => $token->plainTextToken,
            ],
        ]);
    }
}
