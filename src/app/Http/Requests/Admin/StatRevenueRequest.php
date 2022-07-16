<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StatRevenueRequest extends FormRequest
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
            'start_day.required' => 'Vui lòng chọn ngày bắt đầu',
            'start_day.date' => 'Ngày bắt đầu không đúng định dạng',
            'end_day.required' => 'Vui lòng chọn ngày kết thúc',
            'end_day.date' => 'Ngày kết thúc không đúng định dạng',
        ];
    }
}
