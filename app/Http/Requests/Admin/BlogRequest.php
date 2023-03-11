<?php

namespace App\Http\Requests\Admin;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
//            'category_id' => 'required',
            'description' => 'required',
            'excerpt.*' => 'max:255',
            'published_date' => 'required',
//            'excerpt.' . $preferred_language => 'required',
        ];
        return $rules;
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'title.' . $preferred_language . '.required' => "The title field in preferred {$language} language is required.",
//            'excerpt.' . $preferred_language . '.required' => "The excerpt field in preferred {$language} language is required.",
        ];
    }
}
