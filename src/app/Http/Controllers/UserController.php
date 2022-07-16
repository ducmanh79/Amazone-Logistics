<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function changePassword(ChangePasswordRequest $request){
        $new_password = Hash::make($request->new_password);
        User::whereId(auth('api')->user()->id)->update([
            'password' => $new_password,
        ]);
        return response()->json([
            'success' => 'Thay đổi mật khảu thành công',
        ]);
    }
}
