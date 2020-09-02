<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommissionRequest extends FormRequest
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
            'withdraw' => 'required|regex:/^[0-9. -]+$/',
            'trade' => 'required|regex:/^[0-9. -]+$/'
             
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
            'withdraw.required' => 'Withdraw commission is required',
            'withdraw.regex' => 'Invalid withdraw commission',
            'trade.required' => 'Trade commission is required',
            'trade.regex' => 'Invalid Trade commission',
            
        ];
    }
}
