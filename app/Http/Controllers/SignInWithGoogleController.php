<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInWithGoogleRequest;
use App\Models\User;
use Google_Client;

class SignInWithGoogleController extends Controller
{
    public function __invoke(SignInWithGoogleRequest $request)
    {
        $attrs = $request->safe()->toArray();

        $client = new Google_Client(['client_id' => config('services.google.client_id')]);

        /** @var array{email: string, name: string, } $payload*/
        $payload = $client->verifyIdToken($attrs['id_token']);

        if (! $payload) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = User::query()->firstOrCreate([
            'email' => $payload['email'],
            'google_id' => $attrs['google_id'],
        ], [
            'name' => $payload['name'],
            'google_id' => $attrs['google_id'],
            'photo_url' => $payload['picture'],
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
