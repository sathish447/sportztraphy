<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashfreeRequest extends FormRequest
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
            'orderAmount' => 'required|integer|min:1',
            'orderCurrency' => 'required',
            'orderNote' => 'required',
            'customerName' => 'required',
            'customerPhone' => 'required',
            'customerEmail' => 'required',
        ];
    }
}
