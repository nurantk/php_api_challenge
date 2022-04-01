<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeviceRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['success'=>false,'message' => 'Eksik veri','data' => $validator->errors()], 400)
        );
    }

    public function rules(): array
    {
        return [
            'uId' => 'string|required',
            'appId' => 'string|required',
            'operatingSystem' => 'string|required',
            'language' => 'string|required',
        ];
    }


}
