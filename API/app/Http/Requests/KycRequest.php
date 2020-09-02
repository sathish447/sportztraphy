<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class KycRequest extends FormRequest
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
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        }); 

        Validator::extend('check_null', function($attribute, $value)
        { 
            if($value != 'null')
            {
                return true;
            }
            else
            {
                return false;
            }
        });

        return [
            'firstname' => 'required|alpha_spaces|check_null',
            'lastname' => 'required|alpha_spaces|check_null',
            'city' => 'required|alpha_spaces|check_null', 
            'dob' => 'required|check_null',
            'country' => 'required|check_null',
            'proof' => 'required|check_null',
            'doc_num' => 'required|alpha_num|check_null', 
            'exp' => 'required|check_null',
            //'address' => 'required|regex:/(^[-0-9A-Za-z.,\/ ]+$)/|check_null',
            'upload_front_img' => 'required',
            'upload_back_img' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => 'First Name is required',
            'firstname.alpha_spaces' => 'Only accept alphabets',
            'firstname.check_null' =>'First Name is required',
            'lastname.required' => 'Last Name is required',
            'lastname.check_null' => 'Last Name is required',
            'lastname.alpha_spaces' => 'Only accept alphabets',
            'city.required' => 'City is required',
            'city.check_null' => 'City is required',
            'city.alpha_spaces' => 'Only accept alphabets', 
            'dob.required' => 'Date of Birth is required',
            'dob.check_null' => 'Date of Birth is required',
            'country.required' => 'Country is  required',
            'country.check_null' => 'Country is  required',
            'country.alpha_spaces' => 'Only accept alphabets',
            'proof.required' => 'Proof of ID Type is required',
            'proof.check_null' => 'Proof of ID Type is required',
            'doc_num.required' => 'ID Document Number is required',
            'doc_num.check_null' => 'ID Document Number is required',
            'doc_num.alpha_num' => 'ID Document Number may only contain letters and numbers.',
            'exp.required' => 'Expiry Date is required',
            'exp.check_null' => 'Expiry Date is required',
            'address.required' => 'Address is required',
            'address.check_null' => 'Address is required',
            'upload_front_img.required' => 'ID Front Document is required',
            'upload_back_img.required' => 'ID Back Document is required',
            'upload_front_img.images' => 'Only Allowed (png,jpg,jpeg)',
            'upload_back_img.images' => 'Only Allowed (png,jpg,jpeg)',
        ];
    }
}
