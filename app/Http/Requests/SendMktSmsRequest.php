<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMktSmsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'receptors' => 'required|array|min:2|max:200',
            'receptors.*' => 'numeric|ir_mobile:zero|distinct',
            'message' => 'required|string|persian_alpha_eng_num|max:256'
        ];
    }

    public function messages()
    {
        return [
            'receptors.required' => 'The receptor field is required.',
            'receptors.array' => 'The receptor must be an array.',
            'receptors.min' => 'You must provide at least two receptors.',
            'receptors.max' => 'You cannot provide more than 200 receptors.',
            'receptors.*.numeric' => 'Each receptor must be a valid numeric phone number.',
            'receptors.*.ir_mobile' => 'Each receptor must be a valid Iranian mobile number starting with 09.',
            'message.required' => 'The message field is required.',
            'message.string' => 'The message must be a string.',
            'message.persian_alpha_eng_num' => 'The message can only contain Persian letters, numbers, and English numbers.',
            'message.max' => 'The message may not be greater than 256 characters.'
        ];
    }
}