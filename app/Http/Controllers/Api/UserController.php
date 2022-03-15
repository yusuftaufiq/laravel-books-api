<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

final class UserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \App\Http\Resources\UserResource
     */
    final public function __invoke(Request $request)
    {
        return new UserResource($request->user());
    }
}
