<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JyotiCareRequest extends FormRequest
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
    public function rules(\Illuminate\Http\Request $request)
    {
        return [
            'email_address' => 'required|email',
            'full_name' => 'required',
            'mobile_no' => 'required',
            'address' => 'required',
            'qualification' => 'required',
            'branch' => 'required',
            'status_category' => 'required',
            'citizenship_file' => 'required',
            'captcha' => 'required|captcha'
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Please Enter Your Name.',
            'subject.required' => 'Please Enter Subject.',
            'email_address.required' => 'Please Enter Valid Email Address.',
            'address.required' => 'Please Enter Address.',
            'mobile_no.required' => 'Please Enter Valid Mobile Number.',
            'qualification.required' => 'Please Select Qualification.',
            'branch.required' => 'Please Select Branch.',
            'citizenship_file.required' => 'Please Select Citizenship File.',
            'status_category.required' => 'Please Select Status.',
            'g-recaptcha-response.required' => 'Please Enter Google Recaptcha Response.',
        ];
    }
}