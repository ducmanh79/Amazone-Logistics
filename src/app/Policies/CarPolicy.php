<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
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
        if($user->permissions()->where('name','create_car')->orWhere('name','edit_car')->orWhere('name', 'confirm_collected_money_from_car')
            ->orWhere('name', 'cancel_confirm_collected_money_from_car')->orwhere('name','create_carrental')->orWhere('name','edit_carrental')->orWhere('name', 'load_goods_on_car')->exists()){
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
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Car $car)
    {
        if($user->permissions()->where('name','create_car')->orWhere('name','edit_car')->orWhere('name', 'confirm_collected_money_from_car')
        ->orWhere('name', 'cancel_confirm_collected_money_from_car')->orwhere('name','create_carrental')->orWhere('name','edit_carrental')->exists()){
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
        if($user->permissions()->where('name','create_car')->exists()){
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
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Car $car)
    {
        if($user->permissions()->where('name','edit_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Car $car)
    {
        if($user->permissions()->where('name','delete_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Car $car)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Car $car)
    {
        //
    }
    public function getAllCar(User $user){
        if($user->permissions()->where('name','manage_goods_in_car')->orWhere('name', 'cancel_confirm_goods')->exists()){
            return true;
        }
        else{
            return false;
        }
    }
}
