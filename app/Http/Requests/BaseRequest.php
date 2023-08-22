<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function failedValidation(Validator $validator){

        $all = collect($validator->errors()->getMessages())->map(function($item){
            return $item[0];
        });

        $strs = [];
        foreach ($all as $value) {
            $strs[]=  $value;
        }

        throw new HttpResponseException(response()->json([
        'message' => implode(',', $strs),
        'success' => false
       ], 400)); 
        
    }
}
