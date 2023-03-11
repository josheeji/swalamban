<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
            'f_name'=>'required',
            'l_name'=>'required',
            'email'=>'required|email',
            'mobile_no'=>'required|numeric',
//            'departure_date'=>'required',
            'no_person'=>'required|numeric',
            'country_id'=>'required|numeric',
            'message'=>'required',
            'g-recaptcha-response'=>'required'

        ];
   }
        public function messages()
    {
        return [
            'f_name.required' => 'Please Insert Your First Name',
            'l_name.required' => 'Please Insert Your Last Name',
            'email.required' => 'Please Enter Valid Email Address',
            'mobile_no.required' => 'Please Enter Valid Phone No',
//            'departure_date.required' => 'Please Enter Departure Date',
            'no_person.required' => 'Please Enter No of Person',
            'country_id.required' => 'Please Select Your Country',
            'message.required' => 'Please Enter Message',
            'g-recaptcha-response.required' => 'Please Enter Google Recaptcha Response'
        ];
  
    }
}
