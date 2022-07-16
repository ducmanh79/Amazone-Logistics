<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Models\AppConst;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'employee');
    }
    public function index(Request $request)
    {
        $employees = User::where('role_id', '<>', 1)->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $employees;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        $permissionQuery = Permission::query();
        if($request->role_id == 2){
            $permissionQuery->orWhere('name', 'confirm_goods')
            ->orWhere('name', 'load_goods_on_car');
        }
        else{
            $permissionQuery->orWhere('name', 'create_carrental')
            ->orWhere('name', 'create_car')->orWhere('name', 'create_cost_of_car')->orWhere('name', 'confirm_pay_ware_house');
        }
        $permission_id = $permissionQuery->pluck('id');
        $employee = new User();
        $employee->fill($request->all());
        $employee->password = Hash::make($request->phone);
        $employee->save();
        $employee->permissions()->attach($permission_id);
        return $employee;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $employee)
    {

        return $employee;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $employee)
    {
        return $employee;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        if($employee->role_id != $request->role_id){
            $employee->permissions()->detach();
            $permissionQuery = Permission::query();
            if($request->role_id == 2){
                $permissionQuery->orWhere('name', 'confirm_goods')
                ->orWhere('name', 'load_goods_on_car');
            }
            else{
                $permissionQuery->orWhere('name', 'create_carrental')
                ->orWhere('name', 'create_car')->orWhere('name', 'create_cost_of_car')->orWhere('name', 'confirm_pay_ware_house');
            }
            $permission_id = $permissionQuery->pluck('id');
            $employee->permissions()->attach($permission_id);

        }
        $employee->fill($request->all());
        $employee->save();
        return $employee;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $employee)
    {
        // $employee->delete();
        // return response()->json([
        //     'success' => 'Xóa nhân viên thành công',
        // ]);
    }

    public function resetPassword(User $user){
        $this->authorize('resetPassword', User::class);
        $user->password = Hash::make($user->phone);
        $user->save();
        return response()->json([
            'success' => 'Reset mật khẩu thành công',
        ]);
    }

    public function activeEmployee(User $user){
        if($user->role->name == "admin"){
            return response()->json([
                'message' => 'Không thể vô hiệu hóa tài khoản này',
            ]);
        }
        if($user->isActive == true){
            $user->isActive = false;
            $user->save();
            return response()->json([
                'message' => 'Vô hiệu hóa tài khoản thành công',
            ]);
        }
        else{
            $user->isActive = true;
            $user->save();
            return response()->json([
                'message' => 'Hủy vô hiệu hóa tài khoản thành công',
            ]);
        }
    }
}
