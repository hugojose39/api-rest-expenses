<?php

namespace App\Http\Controllers;

use App\Http\Requests\TokenRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\TokenResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenController
{
    public function __construct(private readonly User $user)
    {
    }

    public function __invoke(TokenRequest $request): JsonResponse|JsonResource
    {
        $input = $request->validated();

        $user = $this->user->where('email', $input['email'])->firstOrFail();

        $token = $user->createToken($input['token_name']);

        return new TokenResource($token);
    }
}
