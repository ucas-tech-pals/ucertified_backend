<?php

namespace App\Http\Requests\V1\Auth\InstitutionAuth;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class RegisterRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|string|unique:institutions,email,'.$this->id,
            'password' =>'required|string|confirmed',
            /*'password' => [
                'required',
                'confirmed'
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed'
            ]*/
        ];
    }

    public function messages()
    {
        return [   
            'name.required' => "Name is required.",
            'email.required' => "Email is Required.",
            'email.email' => "Email should be an correct email format.",
            'email.unique' => "This email is used.",
            'password.required' => "Password is Required",
            'password.confirmed' => "Password and confirmed password do not match.",
            /*'password.required' => "Password is Required",
            'password.min' => "Min password length is 8 characters",
            'password.regex' => "password must contains at leat one uppercaser char, one lowercase char, one spetial char and one digit number",
            */
        ];
    }
}
