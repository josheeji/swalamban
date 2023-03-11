<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArticleCommentRequest extends FormRequest
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
            'full_name' => 'required',
            'email' =>'required|email',
            'comment' =>'required',
            'g-recaptcha-response'=>'required'
            // 'short_description' => 'required',
            // 'description' => 'required'
        ];
    }
    public function messages(){
        return [
            'title.required' =>'Full Name is Required',
            'email.required' =>'Valid Email address is Required',
            'comment.required' =>'Comment is Required',
            'g-recaptcha-response.required' =>'Validate Captcha'

            // 'short_description.required' =>'Please insert Short Description',
            // 'description.required' =>'Please insert Description'
        ];
    }
}
