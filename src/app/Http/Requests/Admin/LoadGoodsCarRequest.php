<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoadGoodsCarRequest extends FormRequest
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
            'car_id' => 'required|numeric',
            'goods' => 'required|array|min:1',
            'goods.*.goods_id' => 'required',
            'goods.*.fareOfCar' => 'required|numeric'
        ];
    }

    public function messages(){
        return [
            'car_id.numeric' => 'Vui lòng chọn xe để xếp mặt hàng lên',
            'car_id.required' => 'Vui lòng chọn xe để xếp mặt hàng lên',
            'goods.*.goods_id.required' => 'Vui lòng chọn mặt hàng cần xếp lên xe',
            'goods.*.fareOfCar.required'=> 'Vui lòng nhập cước phí xe',
            'goods.*.fareOfCar.numeric' => 'Cước phí của xe không hợp lệ',
            'goods.required' => 'Vui lòng chọn mặt hàng để xếp lên xe',
            'goods.min' => 'Vui lòng chọn mặt hàng để xếp lên xe',
        ];
    }
}
