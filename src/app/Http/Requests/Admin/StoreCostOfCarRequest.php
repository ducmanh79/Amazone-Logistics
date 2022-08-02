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
            'name.required' => 'Please enter name of cost',
            'date.required' => 'Please enter date created cost',
            'date.before' => 'The date should not be later than today',
            'cost.required' => 'Please enter cost',
            'cost.numeric' => 'Invalid cost',
        ];
    }
}
