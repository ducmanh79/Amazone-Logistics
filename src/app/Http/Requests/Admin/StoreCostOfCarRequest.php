<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCostOfCarRequest extends FormRequest
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
            'date' => 'required|before:tomorrow',
            'cost' => 'required|numeric',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Vui lòng nhập tên chi phí',
            'date.required' => 'Vui lòng chọn ngày nhập chi phí',
            'date.before' => 'Không nhập ngày quá hôm nay',
            'cost.required' => 'Vui lòng nhập chi phí',
            'cost.numeric' => 'Chi phí không hợp lệ',
        ];
    }
}
