<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignInWithGoogleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_token' => [
                'required',
                'string',
            ],
            'google_id' => [
                'required',
                'string',
            ],
        ];
    }
}
