<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['success'=>false,'message' => 'Error','errors' => $validator->errors()], 400)
        );
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'clientToken' => 'string|required',
            'receiptId' => 'string|required',
        ];
    }


}
