<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFareOfCarRequest extends FormRequest
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
            'fareOfCar' => 'required|numeric',
        ];
    }

    public function messages(){
        return [
            'fareOfCar.required' => 'Vui lòng nhập chi phí lên xe cho mặt hàng',
            'fareOfCar.numeric' => 'Chi phí lên xe phải là số',
        ];
    }
}
