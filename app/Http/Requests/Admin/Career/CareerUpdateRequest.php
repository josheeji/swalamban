<?php

namespace App\Http\Requests\Admin\Career;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class CareerUpdateRequest extends FormRequest
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
            // 'location' => 'required',
             'file' => 'nullable|max:5120|mimes:jpeg,jpg,png,pdf,doc,docx',
            'publish_from' => 'required|date',
            'publish_to' => 'required|date|after_or_equal:publish_from'
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'title.' . $preferred_language . '.required' => "The title field in preferred {$language} language is required.",
            // 'description.' . $preferred_language . '.required' => "The description field in preferred {$language} language is required.",
            'publish_to.after_or_equal' => 'The end date field must be a date after or equal to publish date.',
            // 'file.required' => 'File field is required.',
            // 'file.max' => 'File size exceeded. Max file size allowed 5 Mb.'
        ];
    }
}