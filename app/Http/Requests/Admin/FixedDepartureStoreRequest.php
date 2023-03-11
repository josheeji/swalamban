<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FixedDepartureStoreRequest extends FormRequest
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
            'year' => 'required',
            'departure_date' => 'required',
            'return_date' => 'required',
        ];
    }

      public function messages(){
        return [
            'year.required' =>'Year is Required',
            'departure_date.required' =>'Please insert the Departure Date',
            'return_date.required' =>'Please insert the Departure Date',

        ];
    }
}
