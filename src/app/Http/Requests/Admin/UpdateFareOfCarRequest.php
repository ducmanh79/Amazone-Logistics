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
            'fareOfCar.required' => 'Please enter fare of car for this product',
            'fareOfCar.numeric' => 'Fare of car must be number',
        ];
    }
}
