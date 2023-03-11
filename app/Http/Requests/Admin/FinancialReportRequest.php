<?php

namespace App\Http\Requests\Admin;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class FinancialReportRequest extends FormRequest
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
            'company_id' => 'required',
            'file' => 'max:32768|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx',
            'published_date' => 'required',
            'category_id' => 'required',
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'title.' . $preferred_language . '.required' => "The title field in preferred {$language} language is required.",
            'file.max' => 'File size exceeded. Max file size allowed 32Mb.'
        ];
    }
}