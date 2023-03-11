<?php

namespace App\Http\Requests\Admin;

use App\Rules\ValidateRecaptcha;
use Illuminate\Foundation\Http\FormRequest;

class PriceRangeStoreRequest extends FormRequest
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
            'traveller_range' => 'required|unique:price_ranges'
        ];
        return $rules;
    }
}
