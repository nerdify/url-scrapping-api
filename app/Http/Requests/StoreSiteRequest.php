<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
            ],
            'url' => [
                'required',
                'url',
            ],
            'scan_interval' => [
                'required',
                'integer',
                'min:1',
            ],
            'scan_interval_type' => [
                'required',
                'string',
                'in:min,hour,day,week,month,year',
            ],
            'dispatch_now' => [
                'required',
                'boolean',
            ],
        ];
    }
}
