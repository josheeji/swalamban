<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DestinationStoreRequest extends FormRequest
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
        $rules = [
            'name' => 'required|unique:destinations|max:255',
            'description' => 'required',
            'image' =>'required'
            //'image' => 'mimes:jpeg,jpg,png',
        ];

        if ($this->id) {
            $rules['name'] = 'required|unique:destinations,name,' . $this->id . ',id';
        }

        return $rules;
    }
}
