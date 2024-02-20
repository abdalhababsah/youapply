<?php

namespace App\Http\Requests\Password;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|exists:users,phone',
            'sms_code' => 'required',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
