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
            'nameSender.required' => 'Vui lòng nhập tên người gửi',
            'addressSender.required' => 'Vui lòng nhập địa chỉ người gửi',
            'phoneSender.required' => 'Vui lòng nhập số điện thoại người gửi',
            'nameReceiver.required' => 'Vui lòng nhập tên người nhận',
            'addressReceiver.required' => 'Vui lòng nhập địa chỉ người nhận',
            'phoneReceiver.required' => 'Vui lòng nhập số điện thoại người nhận',
            'goods.*.name.required' => 'Vui lòng nhập tên mặt hàng',
            'goods.*.quantity.required' => 'Vui lòng nhập só lượng cho mặt hàng',
            'goods.*.quantity.numeric' => 'Số lượng phải là số',
            'goods.*.unit.required' => 'Vui lòng nhập đơn vị cho mặt hàng',
            'goods.*.fare.required' => 'Vui lòng nhập cuớc phí cho mặt hàng',
            'goods.*.fare.numeric' => 'Cước phí mặt hàng phải là số',
        ];
    }
}
