<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiRequest;

class LoginRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'email' => ['nullable', 'string'],
            'username' => ['nullable', 'string'],
            'login' => ['nullable', 'string'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hasLogin = !empty($this->email) || !empty($this->username) || !empty($this->login);
            if (!$hasLogin) {
                $validator->errors()->add('email', 'Email atau Username wajib diisi.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ];
    }
}
