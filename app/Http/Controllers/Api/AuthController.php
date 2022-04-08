<?php

namespace App\Http\Controllers\Api;

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
/**
 * Create a new personal access token.
 *
 * @param \Illuminate\Http\Request $request
 *
 * @return \App\Http\Resources\PersonalAccessTokenResource
 */
public function login(LoginRequest $request): PersonalAccessTokenResource
{
$request->authenticateOrFail();

$user = $request->user();

$tokenName = $request->input('token_name');
$expiredAt = $request->date(key: 'expired_at', format: 'Y-m-d');

$token = $user->createExpirableToken(name: $tokenName, expiredAt: $expiredAt);

$tokenResource = new PersonalAccessTokenResource($token->accessToken);
$tokenResource->withResponse($request, new JsonResponse(status: Response::HTTP_CREATED));
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
public function logout(Request $request): PersonalAccessTokenResource
{
$user = $request->user();

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
