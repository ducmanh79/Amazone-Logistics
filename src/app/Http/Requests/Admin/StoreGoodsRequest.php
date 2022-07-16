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
            'name.required' => 'Vui lòng nhập tên mặt hàng',
            'quantity.required' => 'Vui lòng nhập só lượng mặt hàng',
            'unit.required' => 'Vui lòng nhập đơn vị cho mặt hàng',
            'fare.required' => 'Vui lòng nhập chi phí cho mặt hàng',
            'collectedMoney.required' => 'Vui lòng nhập tiền thu hộ',
            'collectedMoney.numeric' => 'Tiền thu hộ phải là số',
            'fare.numeric' => 'Chi phí phải là số',
            'quantity.numeric' => 'Số lượng phải là số',
        ];
    }
}
