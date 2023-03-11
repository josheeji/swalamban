<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ItineraryUpdateRequest extends FormRequest
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
            'title' => 'required',
            'image' =>'nullable',
            'short_description' => 'required',
            'description' => 'required'
        ];
    }
    public function messages(){
        return [
            'title.required' =>'Title Must be Required',
            'image.required' =>'Please insert the valid Image',
            'short_description.required' =>'Please insert Short Description',
            'description.required' =>'Please insert Description'
        ];
    }
}
