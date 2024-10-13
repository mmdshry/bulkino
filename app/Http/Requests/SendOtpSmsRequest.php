<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpSmsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'receptor' => 'required|numeric|ir_mobile:zero',
            'token' => 'required|numeric|min_digits:1|max_digits:10',
        ];
    }

    public function messages()
    {
        return [
            'receptor.required' => 'The receptor field is required.',
            'receptor.numeric' => 'The receptor must be a valid numeric phone number.',
            'receptor.ir_mobile' => 'The receptor must be a valid Iranian mobile number starting with 09.',
            'token.required' => 'The token field is required.',
            'token.numeric' => 'The token must be a numeric value.',
            'token.min_digits' => 'The token must have at least 1 digit.',
            'token.max_digits' => 'The token may not have more than 10 digits.'
        ];
    }
}