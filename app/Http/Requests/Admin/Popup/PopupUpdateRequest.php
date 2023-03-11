<?php

namespace App\Http\Requests\Admin\Popup;

use Illuminate\Foundation\Http\FormRequest;

class PopupUpdateRequest extends FormRequest
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
            'image' => 'nullable|max:2048|mimes:jpeg,jpg,bmp,png,pdf',
        ];
    }

    public function messages()
    {
        return [];
    }
}
