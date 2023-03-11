<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantStoreRequest extends FormRequest
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
            'email' => 'required|email',
            'contact_no' => 'required',
            'address' => 'required',
            'message' => 'required|max:250',
            'resume' => 'required|max:2048|mimes:jpeg,jpg,png,pdf,doc,docx',
            'cover_letter' => 'required|max:2048|mimes:jpeg,jpg,png,pdf,doc,docx',
            'captcha' => 'required|captcha'
        ];
    }
}
