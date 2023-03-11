<?php

namespace App\Http\Requests\Admin;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class SyllabusRequest  extends FormRequest
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
            'category.' . $preferred_language => 'required',
            'designation.' . $preferred_language => 'required',
            'file' => 'max:20480|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx',
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'category.' . $preferred_language . '.required' => "The Category field in preferred {$language} language is required.",
            'designation.' . $preferred_language . '.required' => "The Designation field in preferred {$language} language is required.",
            'file.max' => 'File size exceeded. Max file size allowed 20Mb.'
        ];
    }
}
