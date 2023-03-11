<?php

namespace App\Http\Requests\Admin\Associate;

use Illuminate\Foundation\Http\FormRequest;

class AssociateStoreRequest extends FormRequest
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
            'feature_image' => 'image|max:2048|mimes:jpeg,bmp,png,jpg',
        ];
    }

    public function messages()
    {
        return [
            'feature_image.image' => 'Invalid file type.',
            'feature_image.max' => 'The image may not be greater than 2Mb.',
        ];
    }
}
