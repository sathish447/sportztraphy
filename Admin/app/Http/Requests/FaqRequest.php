<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'heading' => 'required|regex:/^[a-z,A-Z,0-9 ]+$/', 
            'description' => 'required|regex:/^[a-z,A-Z,0-9 ]+$/', 
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
            'heading.required' => 'Message is required',
            'heading.required' => 'Invalid message format',
            'description.required' => 'Description is required',
            'description.regex' => 'Invalid description format' 
        ];
    }
}
