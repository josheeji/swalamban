<?php

namespace App\Http\Requests\Admin\Gallery;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class GalleryUpdateRequest extends FormRequest
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

        return [
            'title.' . $preferred_language => 'required',
            // 'description.' . $preferred_language => 'required',
            'image' => 'image|max:2048|mimes:jpeg,jpg,bmp,png'
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'title.' . $preferred_language . '.required' => "The title field in preferred {$language} language is required.",
            // 'description.' . $preferred_language . '.required' => "The description field in preferred {$language} language is required."
        ];
    }
}
