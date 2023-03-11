<?php

namespace App\Http\Requests\Admin;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class TeamEditRequest extends FormRequest
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
            'full_name.' . $preferred_language => 'required',
            'designation.' . $preferred_language => 'required',
            'photo' => 'image|max:2048 | mimes:png,jpg,jpeg',
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'category.required' => 'The category field is required.',
            'full_name.' . $preferred_language . '.required' => "The full name field in preferred {$language} language is required.",
            'designation.' . $preferred_language . '.required' => "The designation field in preferred {$language} language is required.",
            'photo.max' => "Maximum file size to upload is 2MB (2048 KB)"
        ];
    }
}