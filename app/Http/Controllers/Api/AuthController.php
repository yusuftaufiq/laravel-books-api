<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserInterface;
use App\Enums\TokenStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\PersonalAccessTokenResource;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuthController extends Controller
{
    final public function __construct(
        private UserInterface $user,
    ) {
    }

    /**
     * Create a new personal access token.
     *
     * @param \App\Http\Requests\LoginRequest $request
     *
     * @return \App\Http\Resources\PersonalAccessTokenResource
     */
    final public function login(LoginRequest $request): PersonalAccessTokenResource
    {
        $request->authenticate();

        /** @var User */
        $user = $request->user();

        $token = $user->createExpirableToken(
            name: $request->get('token_name'),
            expiredAt: \DateTime::createFromFormat('Y-m-d H:i:s', "{$request->get('expired_at')} 23:59:59"),
        );

        $tokenResource = new PersonalAccessTokenResource($token->accessToken);
        $tokenResource->with['status'] = Response::HTTP_CREATED;
        $tokenResource->with['title'] = Response::$statusTexts[$tokenResource->with['status']];
        $tokenResource->withResponse($request, new JsonResponse(status: $tokenResource->with['status']));
        $tokenResource->additional([
            'token' => [
                'type' => 'Bearer',
                'plain_text' => $token->plainTextToken,
                'status' => TokenStatusEnum::Active,
            ],
        ]);

        return $tokenResource;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \App\Http\Resources\PersonalAccessTokenResource
     */
    final public function logout(Request $request): PersonalAccessTokenResource
    {
        /** @var User */
        $user = $request->user();

        /** @var PersonalAccessToken */
        $token = $user->currentAccessToken();

        $token->delete();

        return (new PersonalAccessTokenResource($token))->additional([
            'token' => [
                'type' => 'Bearer',
                'status' => TokenStatusEnum::Revoked,
            ],
        ]);
    }
}
