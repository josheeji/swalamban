<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
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
            'department_id' => 'required',
            'doctor_id' => 'required',
            'date' => 'required',
            'doctor_time_slot_id' => 'required',
            'full_name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'message' => 'required'
        ];
    }
}
