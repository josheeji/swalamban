<?php

namespace App\Http\Requests\Admin\Forex;

use Illuminate\Foundation\Http\FormRequest;

class StoreForexRequest extends FormRequest
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
            'Forex.*.FXD_CRNCY_CODE' => 'required',
            'Forex.*.FXD_CRNCY_UNITS' => 'required',
            'Forex.*.BUY_RATE' => 'required',
            'Forex.*.BUY_RATE_ABOVE' => 'required',
            'Forex.*.SELL_RATE' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'RTLIST_DATE.required' => 'Date field is required.',
            'Forex.*.BUY_RATE.required' => 'Buy rate field is required.',
            'Forex.*.BUY_RATE_ABOVE.required' => 'Buy rate above field is required',
            'Forex.*.SELL_RATE.required' => 'Sell rate field is required.'
        ];
    }
}
