<?php

namespace App\Http\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpWithEmailAndPasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class SignUpWithEmailAndPasswordController extends Controller
{
    public function __invoke(SignUpWithEmailAndPasswordRequest $request): JsonResponse
    {
        $attrs = $request->safe()->toArray();

        $user = User::create($attrs);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
