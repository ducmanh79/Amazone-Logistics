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
            'car_id.numeric' => 'Please select truck to load product',
            'car_id.required' => 'Please select truck to load product',
            'goods.goods_id.required' => 'Please select products to load on truck',
            'goods.fareOfCar.required'=> 'Please enter fare of truck',
            'goods.fareOfCar.numeric' => 'Invalid fare',
            'goods.required' => 'Please select products to load on truck',
            'goods.min' => 'Please select products to load on truck',
        ];
    }
}
