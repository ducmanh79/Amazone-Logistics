<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'nameSender' => 'required',
            'addressSender' => 'required',
            'phoneSender' => 'required',
            'nameReceiver' => 'required',
            'addressReceiver' => 'required',
            'phoneReceiver' => 'required',
            'goods.*.name' =>'required',
            'goods.*.quantity' => 'required|numeric',
            'goods.*.unit' => 'required',
            'goods.*.fare' => 'required|numeric'
        ];
    }

    public function messages(){
        return [
            'nameSender.required' => 'Please enter sender name',
            'addressSender.required' => 'Please enter sender address',
            'phoneSender.required' => 'Please enter sender mobile number',
            'nameReceiver.required' => 'Please enter receiver name',
            'addressReceiver.required' => 'Please enter receiver address',
            'phoneReceiver.required' => 'Please enter receiver mobile number',
            'goods.*.name.required' => 'Please enter product name',
            'goods.*.quantity.required' => 'Please enter product quantity',
            'goods.*.quantity.numeric' => 'Quantity must be numeric',
            'goods.*.unit.required' => 'Please enter product unit',
            'goods.*.fare.required' => 'Please enter shipment fare of this product',
            'goods.*.fare.numeric' => 'Shipment fare must be a number',
        ];
    }
}
