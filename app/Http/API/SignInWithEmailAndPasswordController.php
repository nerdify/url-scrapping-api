<?php

namespace App\Http\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInWithEmailAndPasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SignInWithEmailAndPasswordController extends Controller
{

    public function __invoke(SignInWithEmailAndPasswordRequest $request): JsonResponse
    {
        $attrs = $request->safe()->toArray();

        if(!Auth::attempt($attrs)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::query()->where('email', $attrs['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
