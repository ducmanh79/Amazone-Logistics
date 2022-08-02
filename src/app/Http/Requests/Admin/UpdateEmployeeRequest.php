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
            'phone.unique' => 'Mobile number already exists',
            'phone.required' => 'Please enter employee mobile number',
            'name.required' => 'Please enter employee name',
            'salary.required' => 'Please enter employee salary',
            'salary.numeric' => 'Invalid salary',
            'idNumber.required' => 'Please enter employee id number',
            'address.required' => 'Please enter employee address',
            'role_id.required' => 'Please select employee position',

        ];
    }
}
