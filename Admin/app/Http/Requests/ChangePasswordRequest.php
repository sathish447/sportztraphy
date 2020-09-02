<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'oldpassword' => 'required',
            'newpassword' => 'required |min:8',
            'confirmpassword' => 'required |min:8|same:newpassword',
             
        ];
    }

     /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'oldpassword'             => trans('Current Password'),
            'newpassword'             => trans('New Password'),
            'confirmpassword'       => trans('Confirm Password'),
        ];
    }




     /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function message()
    {
        
        return [
            'oldpassword.required' => 'Current Password is required',
            'newpassword.required' => 'New Password is required',
            'confirmpassword.required' => 'Confirm New Password is required',
            'confirmpassword.same' => 'Wrong confirm password'            
        ];
    }
}
