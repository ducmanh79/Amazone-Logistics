<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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

    public function validationData()
    {
        $data = $this->all();
        $data['employee'] = $this->route('employee');
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
            'phone' => 'required|unique:users,phone,'.$this->employee->id,
            'name' => 'required|string',
            'salary' => 'required|numeric',
            'idNumber' => 'required',
            'address' => 'required',
            'role_id' => 'required',
        ];
    }

    public function messages(){
        return [
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'phone.required' => 'vui lòng nhập số điện thoại',
            'name.required' => 'vui lòng nhập tên nhân viên',
            'salary.required' => 'Vui lòng nhập lương cho nhân viên',
            'salary.numeric' => 'Lương không hợp lệ',
            'idNumber.required' => 'Vui lòng nhập số chứng minh thư nhân dân',
            'address.required' => 'Vui lòng nhập địa chỉ cho nhân viên',
            'role_id.required' => 'Vui lòng chọn vị trí của nhân viên',

        ];
    }
}
