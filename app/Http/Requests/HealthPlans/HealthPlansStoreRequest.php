<?php

namespace App\Http\Requests\HealthPlans;

use Illuminate\Foundation\Http\FormRequest;

class HealthPlansStoreRequest extends FormRequest
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
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'mobile_no' => 'required',
            'address' => 'required',
            'departure_date' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'f_name.required' => 'First name is required.',
            'l_name.required'  => 'Last name is required.',
            'mobile_no.required' => 'Mobile no. is required.',
            'departure_date.required' => 'Appointment date is required.'
        ];
    }
}
