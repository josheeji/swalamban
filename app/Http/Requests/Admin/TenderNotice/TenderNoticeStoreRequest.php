<?php

namespace App\Http\Requests\Admin\TenderNotice;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class TenderNoticeStoreRequest extends FormRequest
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
            'excerpt.*' => 'max:255',
            'image' => 'image|max:2048|mimes:jpeg,bmp,png,jpg',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ];

        return $rules;
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'title.' . $preferred_language . '.required' => "The title field in preferred {$language} language is required.",
            'excerpt.*.max' => "The excerpt field may not be greater than 255 characters.",
            'image.max' => 'The image may not be greater than 2Mb.',
        ];
    }
}
