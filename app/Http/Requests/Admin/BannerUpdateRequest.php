<?php

namespace App\Http\Requests\Admin;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class BannerUpdateRequest extends FormRequest
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
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;

        $rules = [
            'title.' . $preferred_language => 'required',
            'caption.*' => 'max:255',
            'image' => 'image|max:2048|mimes:jpeg,bmp,png,jpg'
        ];

        return $rules;
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'title.' . $preferred_language . '.required' => "The title field in preferred {$language} language is required.",
            'image.image' => 'Invalid file type.',
            'image.max' => 'The image may not be greater than 2Mb.',
        ];
    }
}
