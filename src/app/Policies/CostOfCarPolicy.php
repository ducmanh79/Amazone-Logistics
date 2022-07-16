<?php

namespace App\Policies;

use App\Models\CostOfCar;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostOfCarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */

    public function before(User $user){
        if($user->role->name == "admin"){
            return true;
        }
        return null;
    }
    public function viewAny(User $user)
    {
        if($user->permissions()->orWhere('name','create_cost_of_car')->orWhere('name','edit_cost_of_car')->orWhere('name','delete_cost_of_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CostOfCar  $costOfCar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CostOfCar $costOfCar)
    {
        if($user->permissions()->where('name','create_cost_of_car')->orWhere('name','edit_cost_of_car')->orWhere('name','delete_cost_of_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if($user->permissions()->where('name','create_cost_of_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CostOfCar  $costOfCar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CostOfCar $costOfCar)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CostOfCar  $costOfCar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CostOfCar $costOfCar)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CostOfCar  $costOfCar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CostOfCar $costOfCar)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CostOfCar  $costOfCar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CostOfCar $costOfCar)
    {
        //
    }

    public function updateCostOfCar(User $user){
        if($user->permissions()->where('name','edit_cost_of_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function deleteCosOfCar(User $user){
        if($user->permissions()->where('name','delete_cost_of_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }
}
