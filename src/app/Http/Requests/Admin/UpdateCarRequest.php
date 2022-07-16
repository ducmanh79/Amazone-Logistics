<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return [
            'licensePlate' => 'required|unique:cars,licensePlate'.$this->car->id,
            'phoneNumber' => 'required',
        ];
    }

    public function validationData()
    {
        $data = $this->all();
        $data['car'] = $this->route('car');
        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'licensePlate.required' => 'Vui lòng nhập biển số xe',
            'licensePlate.unique' => 'Xe này đã tồn tại',
            'phoneNumber.required' => 'vui lòng nhập số điện thoại của xe',
        ];
    }
}
