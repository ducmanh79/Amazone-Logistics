<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmCarPayWareHouse extends FormRequest
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
            'goods_id' => 'required',
        ];
    }

    public function messages(){
        return [
            'goods_id.required' => 'Vui lòng chọn mặt hàng cần nhận tiền thu hộ từ tài xế',
        ];
    }
}
