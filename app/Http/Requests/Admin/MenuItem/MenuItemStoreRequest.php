<?php

namespace App\Http\Requests\Admin\MenuItem;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class MenuItemStoreRequest extends FormRequest
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
            'title.' . $preferred_language => 'required_if:type,2',
            'link_url' => 'required_if:type,2'
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'title.' . $preferred_language . '.required_if' => "The title field in preferred {$language} language is required.",
            'link_url.required_if' => 'URL is required'
        ];
    }
}
