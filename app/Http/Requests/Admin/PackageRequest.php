<?php

namespace App\Http\Requests\Admin;

use App\Rules\ValidateRecaptcha;
use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
            'title' => 'required|max:255',
            'description' => 'required',
            'cost' => 'required',
            'category_id' => 'required'
        ];
        return $rules;
    }
}
