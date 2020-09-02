<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
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
            'coin' => 'required', 
            'company_bank' => 'required', 
        ];
    }

    public function message()
    {
        
        return [
            'coin.required' => 'Coin is required', 
            'company_bank.required' => 'Company Account Details is required'
        ];
    }
}
