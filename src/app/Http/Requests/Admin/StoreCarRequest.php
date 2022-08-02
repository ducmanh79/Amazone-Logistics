<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'licensePlate' => 'required|unique:cars',
            'phoneNumber' => 'required',
        ];
    }

    public function messages(){
        return [
            'licensePlate.required' => 'Please enter license plate id',
            'licensePlate.unique' => 'This truck already exits',
            'phoneNumber.required' => 'Please enter truck mobile number',
        ];
    }
}
