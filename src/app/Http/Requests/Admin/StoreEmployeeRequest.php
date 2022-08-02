<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'phone' => 'required|unique:users',
            'name' => 'required|string',
            'salary' => 'required|numeric',
            'idNumber' => 'required',
            'address' => 'required',
            'role_id' => 'required',
        ];
    }

    public function messages(){
        return [
            'phone.unique' => 'Mobile number already exits',
            'phone.required' => 'Please enter mobile number',
            'name.required' => 'Please enter employee name',
            'salary.required' => 'Please enter employee salary',
            'salary.numeric' => 'Invalid salary',
            'idNumber.required' => 'Please enter employee id number',
            'address.required' => 'Please enter employee address',
            'role_id.required' => 'Please enter employee position',

        ];
    }
}
