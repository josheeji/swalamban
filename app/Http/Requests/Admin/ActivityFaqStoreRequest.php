<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ActivityFaqStoreRequest extends FormRequest
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
            'question' => 'required',
            'answer' =>'required'
            // 'short_description' => 'required',
            // 'description' => 'required'
        ];
    }
    public function messages(){
        return [
            'question.required' =>'Please Insert Question',
            'answer.required' =>'Please Insert Answer'
            // 'short_description.required' =>'Please insert Short Description',
            // 'description.required' =>'Please insert Description'
        ];
    }
}
