<?php

namespace App\Http\Requests\Admin\ProductAndService;

use Illuminate\Foundation\Http\FormRequest;

class ProductAndServiceCategoryStoreRequest extends FormRequest
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
            'excerpt' => 'max:255'
        ];
    }
}
