<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignInWithEmailAndPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:dns,rfc',
                Rule::exists(User::class, 'email'),
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
