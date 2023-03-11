<?php

namespace App\Http\Requests\Admin\BranchDirectory;

use Illuminate\Foundation\Http\FormRequest;

class BranchDirectoryStoreRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        $preferred_language = session('site_settings')['preferred_language'] ?? 1;

        return [
            'title.' . $preferred_language . '.required' => 'The title field is required.',
            'province_id.required' => 'The province field is required.',
            'fullname.' . $preferred_language . '.required' => 'The fullname field is required.',
            'email.required' => 'The email field is required.'
        ];
    }
}
