<?php

namespace App\Http\Requests;

use App\Rules\CaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'email_address' => 'nullable',
            'name' => 'nullable',
            'f_name' => 'required',
            'l_name' => 'required',
            'mobile_no' => 'required',
            'message' => 'required',
            'subject' => 'required',
            'captcha' => ['required', new CaptchaRule]
        ];
    }

    public function messages()
    {
        return [
            // 'name.required' => 'Please Enter Your Name.',
            'f_name.required' => 'Please Enter First Name.',
            'l_name.required' => 'Please Enter Last Name.',
            'subject.required' => 'Please Enter Subject.',
            // 'email_address.required' => 'Please Enter Valid Email Address.',
            'mobile_no.required' => 'Please Enter Valid Mobile Number.',
            'message.required' => 'Please Enter Message.',
            'captcha.required' => 'Please Enter Captcha.',
            'g-recaptcha-response.required' => 'Please Enter Google Recaptcha Response.',
        ];
    }
}
