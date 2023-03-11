<?php

namespace App\Http\Requests\Admin\Forex;

use Illuminate\Foundation\Http\FormRequest;

class ImportForexRequest extends FormRequest
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
        return [
            'RTLIST_DATE' => 'required',
            'file' => 'required|file|max:2048|mimes:xls,xlsx',
        ];
    }

    public function messages()
    {
        return [
            'RTLIST_DATE.required' => 'Date field is required.'
        ];
    }
}
