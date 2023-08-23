<?php

namespace App\Http\Requests\InstitutionAuth;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|string|exists:institutions,email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [   
            'email.required' => "Email is Required",
            'email.email' => "email should be an correct email format",
            'password.required' => "Password is Required"
        ];
    }
}
