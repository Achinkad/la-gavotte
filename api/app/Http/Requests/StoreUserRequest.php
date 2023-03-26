<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user)],
            'password' => 'required|min:8'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'The e-mail is already registered. Please, try choose another one.'
        ];
    }
}
