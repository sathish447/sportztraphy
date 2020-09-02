<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPassword extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255|exists:users,email',
        ];
    }

    public function messages()
    {
        return [ 
            'email.required'=> 'Email is required',
            'email.email'=> 'Invalid Email Id',
            'email.exists'=> 'Email is not a registered mail id' 
        ];
    }
}
