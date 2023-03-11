<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Request;

class ArticleStoreRequest extends FormRequest
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

    public function rules( Request $request)
    {

        if(!empty($this->type == 'Link')){
            return [

                'title' => 'required|unique:articles',
                // 'short_description' => 'required',
                // 'url' => 'required|url',
                // 'type' => 'required|not_in:0',
                'image' => 'required'
            ];
        }
        else{
            return [

                'title' => 'required|unique:articles',
                // 'short_description' => 'required',
                // 'url' => 'required',
                // 'type' => 'required|not_in:0',
                'image' => 'required'
            ];
        }

    }

    public function messages()
    {
        return [

            'title.required' => 'Please Insert Title Of Article',
            // 'short_description.required' => 'Please Insert Short Description Of Article',
            // 'url.required' => 'Please Insert url Of Article',
            // 'image.required' => 'Please Upload Image file type jpg,png,jpeg',
            'type.required' => 'Please select the value form list below',
        ];
    }
}