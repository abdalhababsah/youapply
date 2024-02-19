<?php


namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'regex:/^(077|078|079)[0-9]{7}$/', 'unique:users,phone'],
            'password' => 'required|string|min:8',
        ];
    }
}
