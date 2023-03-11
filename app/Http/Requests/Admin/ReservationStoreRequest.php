<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
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
            'first_name' =>'required',
            'last_name' =>'required',
            'address' =>'required',
            'contact' =>'required',
            'mail_address' =>'required',
            'check_in_date'     => 'required|date|date_format:Y-m-d|after:today|before:check_out_date',
            'check_out_date'    => 'required|date|date_format:Y-m-d|after:today',
            'room_id'           => 'sometimes|required',
            'number_of_rooms'   => 'sometimes|required',
            'number_of_adults'   => 'sometimes|required',
            'number_of_childrens'   => 'sometimes|required',
        ];
    }
    public function messages(){
        return [
            'first_name.required' =>'PLease Insert Your FirstName',
            'last_name.required' =>'PLease Insert Your LastName',
            'address.required' =>'PLease Insert Your Address',
            'mail_address.required' =>'PLease Insert Your Mail Address',
            'contact.required' =>'PLease Insert Your Contact',
            'check_in_date.before_or_equal' => 'The check in date must be before or equal to check out date',
            'check_out_date.after_or_equal' => 'The check out date must be after or equal to check in date',

        ];
    }
}
