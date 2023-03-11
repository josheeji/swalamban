<?php

namespace App\Http\Requests\Admin;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class StatisticsRequest extends FormRequest
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
            'year.' . $preferred_language => 'required',
            'earning.' . $preferred_language => 'required|string',
            'expenses.' . $preferred_language => 'required|string',
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'year.' . $preferred_language . '.required' => "The year field in preferred {$language} language is required.",
            'earning.' . $preferred_language . '.required' => "The earning field in preferred {$language} language is required.",
            'expenses.' . $preferred_language . '.required' => "The expenses field in preferred {$language} language is required.",
        ];
    }
}
