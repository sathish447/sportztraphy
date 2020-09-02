<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawCommissionRequest extends FormRequest
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
            'withdraw_limit' => 'required|regex:/^[0-9. -]+$/',
            'withdraw_minimum' => 'required|regex:/^[0-9. -]+$/',
            'withdraw_maximum' => 'required|regex:/^[0-9. -]+$/'
        ];
    }
}
