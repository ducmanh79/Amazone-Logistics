<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoodsRequest extends FormRequest
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
            'name' => 'required',
            'quantity' => 'required|numeric',
            'unit' => 'required',
            'fare' => 'required|numeric',
            'collectedMoney' => 'required|numeric'
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Please enter product name',
            'quantity.required' => 'Please enter product quantity',
            'unit.required' => 'Please enter product unit',
            'fare.required' => 'Please enter product shipment fare',
            'collectedMoney.required' => 'Please enter payment of receiver for this product',
            'collectedMoney.numeric' => 'Payment of receiver must be number',
            'fare.numeric' => 'Shipment fare must be number',
            'quantity.numeric' => 'Quantity must be number',
        ];
    }
}
