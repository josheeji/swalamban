<?php

namespace App\Http\Requests\Admin;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'description.' . $preferred_language => 'required',
            'image' => 'image|max:2048|mimes:jpeg,jpg,bmp,png',
            'banner' => 'image|max:2048|mimes:jpeg,jpg,bmp,png',
            'document' => 'max:2048',
            'category_id' => 'required',
            'excerpt.*' => 'max:255',
//            'excerpt.' . $preferred_language => 'required',
            'published_date' => 'required'
        ];
        return $rules;
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'title.' . $preferred_language . '.required' => "The title field in preferred {$language} language is required.",
            'description.' . $preferred_language . '.required' => "The description field in preferred {$language} language is required.",
            'excerpt.*.max' => "The excerpt field may not be greater than 255 characters.",
//            'excerpt.' . $preferred_language . '.required' => "The excerpt field in preferred {$language} language is required.",
        ];
    }
}
