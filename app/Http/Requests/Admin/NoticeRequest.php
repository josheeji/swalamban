<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NoticeRequest extends FormRequest
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
        $rules = [
            'position' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];

        if (!($this->id)) {
            $rules['image'] = 'required';
        }

        return $rules;
    }
}
