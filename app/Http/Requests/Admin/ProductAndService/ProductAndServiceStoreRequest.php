<?php

namespace App\Http\Requests\Admin\ProductAndService;

use Illuminate\Foundation\Http\FormRequest;

class ProductAndServiceStoreRequest extends FormRequest
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
            'excerpt' => 'max:255',
            'image' => 'image|max:2048|mimes:jpeg,bmp,png,jpg',
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'Invalid file type.',
            'image.max' => 'The image may not be greater than 2Mb.',
        ];
    }
}
