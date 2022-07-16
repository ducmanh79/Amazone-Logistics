<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(User $user){
        $permissionsQuery = Permission::query();
        if($user->role->id == 2){
            $permissionsQuery->where('name', 'confirm_goods')->orWhere('name', 'cancel_confirm_goods')
            ->orWhere('name', 'load_goods_on_car')->orWhere('name', 'cancel_goods_on_the_car')->orWhere('name', 'manage_inventory')
            ->orWhere('name', 'manage_goods_in_car')->orWhere('name', 'edit_fare_of_car');
        }
        elseif($user->role->id == 3){
            $permissionsQuery->where('name', 'create_order')->orWhere('name', 'edit_order')->orwhere('name', 'delete_order')->orWhere('name', 'add_goods')
                ->orWhere('name', 'edit_goods')->orWhere('name', 'delete_goods')->orWhere('name', 'create_carrental')->orWhere('name', 'edit_carrental')
                ->orWhere('name', 'create_car')->orWhere('name', 'edit_car')->orWhere('name','create_cost_of_car')->orWhere('name','edit_cost_of_car')
                ->orWhere('name','delete_cost_of_car')->orWhere('name', 'confirm_collected_money_from_car')
                ->orWhere('name', 'cancel_confirm_collected_money_from_car')->orWhere('name', 'manage_goods_in_car');
        }
        $permissions = $permissionsQuery->get();
        return $permissions;
    }

    public function getPermissionUser(User $user){
        $user->load('permissions');
        return $user;
    }

    public function deletePermission($employee_id, $permission_id){
        $employee = User::with(['permissions'=> function($query) use ($permission_id){
            $query->where('permissions.id',$permission_id);
        }])->find($employee_id);
        if(count($employee->permissions) == 0){
            $employee->permissions()->attach($permission_id);
        }
        else{
            $employee->permissions()->detach($permission_id);
        }
        $employee->tokens()->delete();
        return response()->json([
            'success' => 'Thành công',
        ]);
    }
}
