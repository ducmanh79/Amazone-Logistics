<?php

namespace App\Http\Requests\User;

use App\Rules\passPassword;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'password' => ['required', new passPassword],
            'new_password' => 'required|confirmed'
        ];
    }

    public function messages(){
        return [
            'password.required' => 'Vui lòng nhập mật khẩu',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.confirmed' => 'Nhập lại mật khẩu không đúng',
        ];
    }
}
