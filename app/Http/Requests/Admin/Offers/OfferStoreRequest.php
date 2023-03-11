<?php

namespace App\Http\Requests\Admin\Offers;

use App\Helper\Helper;
use Illuminate\Foundation\Http\FormRequest;

class OfferStoreRequest extends FormRequest
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
            'image' => 'image|max:2048|mimes:jpeg,jpg,bmp,png',
            'banner' => 'required|image|max:2048|mimes:jpeg,jpg,bmp,png',
            'excerpt.*' => 'max:255'
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;
        $language = Helper::getLanguage($preferred_language);
        return [
            'title.' . $preferred_language . '.required' => "The title field in preferred {$language} language is required.",
            'description.' . $preferred_language . '.required' => "The description field in preferred {$language} language is required.",
            'excerpt.*.max' => "The excerpt field may not be greater than 255 characters.",
            'banner.required' => "Banner Image is required."

        ];
    }
}