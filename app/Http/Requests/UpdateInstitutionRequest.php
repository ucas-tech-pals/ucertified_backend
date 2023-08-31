<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstitutionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('institution')->user()->id === $this->institution->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'name' => ['string', 'max:255'],
            'logo' => ['string', 'max:255'],
            'website' => ['string', 'max:255'],
            'phone_number' => ['string', 'max:255'],
            'description' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255', 'unique:institutions'],
            'password' => ['string', 'min:8', 'confirmed'],
        ];
    }
}
