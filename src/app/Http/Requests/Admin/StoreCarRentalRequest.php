<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRentalRequest extends FormRequest
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
            'name' => 'required|string',
            'phoneNumber' => 'required',
            'isHome' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên nhà xe',
            'name.string' => 'Tên nhà xe không hợp lệ',
            'phoneNumber.required' => "Vui lòng nhập số điện thoại",
            'isHome.required' => 'Vui lòng chọn loại nhà xe',
        ];
    }
}
