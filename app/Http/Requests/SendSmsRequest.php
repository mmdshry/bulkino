<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendSmsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'receptor' => 'required|numeric|ir_mobile:zero',
            'message' => 'required|string|max:256'
        ];
    }

    public function messages()
    {
        return [
            'receptor.required' => 'The receptor field is required.',
            'receptor.numeric' => 'The receptor must be a valid numeric phone number.',
            'receptor.ir_mobile' => 'The receptor must be a valid Iranian mobile number starting with 09.',
            'message.required' => 'The message field is required.',
            'message.string' => 'The message must be a string.', 'message.max' => 'The message may not be greater than 256 characters.'
        ];
    }
}