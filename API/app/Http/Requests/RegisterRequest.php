<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class RegisterRequest extends FormRequest
{

    public $test;
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
     * return validation error message that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
            'phone' => 'required|numeric|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'confirm_password' => 'required|string|min:6|different:email',
            'password' => 'required|string|min:6|different:email',            
            'referral' => 'nullable|exists:users,invitecode',
            'registertype' => 'required',
            'registerdevice' => 'required',
        ];
    }

     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {        
        // if($this->input('reqtype') == 'web'){
        //     $this->test = 
        //     $this->failedValidation();
        //     $yourData =['status' => false, 'response' => null, 'message' => $validator->errors()->first()];
        //     throw new HttpResponseException(response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'])); 
        // }else{
            return [
                'phone.required' => 'Mobile Number  is required',
                'name.regex' => 'Name must contain letters',
                'name.max' => 'The maximum length for a name is 25 characters',
                'email.required'=> 'Email is required',
                'email.required'=> 'Email is required',
                'password.required'=> 'Password is required',
                'password.min'=> 'Password must be 6 letters',          
            ];
        //  }
        
    }

       /**
     * Get the validation rules and replay the error message.
     *
     * @return array
     */

    // protected function failedValidation(Validator $validator) { 

    //     if($this->input('reqtype') == 'web'){
    //         $msg = $validator->errors()->all();
    //     } else {
    //         $msg = $validator->errors()->first();
    //     }
    //     $yourData =['status' => false, 'response' => null, 'message' =>$msg ];
    //     throw new HttpResponseException(response()->json($yourData, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'])); 

    // }
}
