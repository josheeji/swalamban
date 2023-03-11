<?php

namespace App\Http\Requests\Admin;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ContentRequest extends FormRequest
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
            'multiData.' . $preferred_language . '.title'  => 'required',
            'image' => 'image|max:2048|mimes:jpeg,jpg,bmp,png',
            'banner' => 'image|max:2048|mimes:jpeg,jpg,bmp,png',
            'multiData.*.excerpt' => 'max:255',
            'publish_at' => 'required'
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'multiData.' . $preferred_language . '.title.required' => "The title field in preferred {$language} language is required.",
            'multiData.' . $preferred_language . '.description.required' => "The description field in preferred {$language} language is required.",
            'multiData.*.excerpt.max' => "The excerpt field may not be greater than 255 characters."
        ];
    }
}
