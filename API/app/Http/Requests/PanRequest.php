<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PanRequest extends FormRequest
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
            'pan_name' => 'required',
            'pan_number' => 'required',
            'dob_pan' => 'required',
            'upload_pan_image' => 'required|file|max:1024',
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
                    
        ];
    }
}
