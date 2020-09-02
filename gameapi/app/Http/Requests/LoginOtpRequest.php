<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginOtpRequest extends FormRequest
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
            'login_otp' => 'required|numeric',
            'otp_session_id' => 'required',
        ];
    }

    public function messages()
    {
        return [ 
            'login_otp.required'=> 'OTP is required.',
            'login_otp.numeric'=> 'THE OTP MUST BE A NUMBER.',
        ];
    }
}
