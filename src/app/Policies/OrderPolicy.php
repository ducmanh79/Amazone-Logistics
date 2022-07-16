<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
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
        if($user->permissions()->where('name','create_order')->orWhere('name','edit_order')->orWhere('name','delete_order')
            ->orWhere('name', 'add_goods')->orWhere('name', 'edit_goods')->orWhere('name', 'delete_goods')
            ->orWhere('name', 'edit_fare_of_car')->orWhere('name', 'cancel_goods_on_the_car')->orWhere('name', 'manage_goods_in_car')
            ->orWhere('name', 'confirm_collected_money_from_car')->orWhere('name', 'cancel_confirm_collected_money_from_car')->exists()){
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Order $order)
    {
        if($user->permissions()->where('name','create_order')->orWhere('name','edit_order')->orWhere('name','delete_order')
            ->orWhere('name', 'add_goods')->orWhere('name', 'edit_goods')->orWhere('name', 'delete_goods')
            ->orWhere('name', 'edit_fare_of_car')->orWhere('name', 'cancel_goods_on_the_car')->orWhere('name', 'manage_goods_in_car')
            ->orWhere('name', 'confirm_collected_money_from_car')->orWhere('name', 'cancel_confirm_collected_money_from_car')->exists()){
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
        if($user->permissions()->where('name','create_order')->exists()){
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Order $order)
    {
        if($user->permissions()->where('name','edit_order')->exists()){
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Order $order)
    {
        if($user->permissions()->where('name','delete_order')->exists()){
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Order $order)
    {
        //
    }

    public function confirmPayCustomer(User $user){
    }

    public function getOrderNotReceive(User $user){
        if($user->permissions()->where('name','create_order')->orWhere('name','edit_order')->orWhere('name','delete_order')
            ->orWhere('name', 'add_goods')->orWhere('name', 'edit_goods')->orWhere('name', 'delete_goods')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function getOrderReceive(User $user){
        if($user->permissions()->where('name','create_order')->orWhere('name','edit_order')->orWhere('name','delete_order')
            ->orWhere('name', 'add_goods')->orWhere('name', 'edit_goods')->orWhere('name', 'cancel_confirm_goods')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function getOrderDelivered(User $user){
        if($user->permissions()->where('name','create_order')->orWhere('name','edit_order')->orWhere('name','delete_order')
            ->orWhere('name', 'add_goods')->orWhere('name', 'edit_goods')->orWhere('name', 'edit_fare_of_car')->orWhere('name', 'cancel_goods_on_the_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

}
