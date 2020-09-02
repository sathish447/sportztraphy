<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPassword extends FormRequest
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
            // 'email' => 'required|string|email',
            'password' => 'required|string|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirmpassword' => 'required|same:password',
        ];
    }

      /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone.required' => 'Mobile Number  is required',
            'name.regex' => 'Name must contain letters',
            'name.max' => 'The maximum length for a name is 25 characters',
            'email.required'=> 'Email is required',
            'email.required'=> 'Email is required',
            'password.required'=> 'Password is required',
            'password.min'=> 'Password must be 6 letters', 
         
        ];
    }
}
