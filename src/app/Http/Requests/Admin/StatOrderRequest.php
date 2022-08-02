<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StatOrderRequest extends FormRequest
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
            'start_day' => 'required|date',
            'end_day' => 'required|date'
        ];
    }

    public function messages(){
        return [
            'start_day.required' => 'Please select start date',
            'start_day.date' => 'Invalid form of start date',
            'end_day.required' => 'Please select end date',
            'end_day.date' => 'Invalid form of end date',
        ];
    }
}
