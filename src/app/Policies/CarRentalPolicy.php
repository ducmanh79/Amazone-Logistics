<?php

namespace App\Policies;

use App\Models\CarRental;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarRentalPolicy
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
        if($user->permissions()->where('name','create_carrental')->orWhere('name','edit_carrental')->orWhere('name', 'confirm_collected_money_from_car')
        ->orWhere('name', 'cancel_confirm_collected_money_from_car')->orWhere('name', 'create_car')->orWhere('name', 'edit_car')->orWhere('name', 'load_goods_on_car')->exists()){
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
     * @param  \App\Models\CarRental  $carRental
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CarRental $carRental)
    {
        if($user->permissions()->where('name','create_carrental')->orWhere('name','edit_carrental')->orWhere('name', 'confirm_collected_money_from_car')
        ->orWhere('name', 'cancel_confirm_collected_money_from_car')->orWhere('name', 'create_car')->orWhere('name', 'edit_car')->orWhere('name', 'load_goods_on_car')->exists()){
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
        if($user->permissions()->where('name','create_carrental')->exists()){
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
     * @param  \App\Models\CarRental  $carRental
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CarRental $carRental)
    {
        if($user->permissions()->where('name','edit_carrental')->exists()){
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
     * @param  \App\Models\CarRental  $carRental
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CarRental $carRental)
    {
        if($user->permissions()->where('name','delete_carrental')->exists()){
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
     * @param  \App\Models\CarRental  $carRental
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CarRental $carRental)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CarRental  $carRental
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CarRental $carRental)
    {
        //
    }
}
