<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrievanceStoreRequest extends FormRequest
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
            // 'branch_id' => 'required',
            // 'department_id' => 'required',
            'full_name' => 'required',
            'mobile' => 'required',
            'email' => 'email|required',
            'subject' => 'required',
            'message' => 'required|max:10000',
            'captcha' => 'required|captcha',
            'grant_authorization' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'branch_id.required' => 'The branch field is required.',
            'department_id.required' => 'The department field is required.',
            'grant_authorization.required' => 'Accept I authorized GBBL & its representative to call me or SMS me with reference to my application.'
        ];
    }
}
