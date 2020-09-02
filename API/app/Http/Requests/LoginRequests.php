<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class LoginRequests extends FormRequest
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
    public function rules(Request $request)
    {        
        return [
            'login_input' => 'required',
            // 'password' => 'required',
        ];
    }

    public function messages()
    {
        return [ 
            'login_input.required'=> 'Email is required',
            'login_input.email'=> 'Invalid Email Id',        
            'login_input.exists'=> 'Given Email is not Register',
            'password.required'=> 'Password is required' 
        ];
    }
}
