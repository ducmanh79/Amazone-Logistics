<?php

namespace App\Policies;

use App\Models\Goods;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoodsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Goods $goods)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Goods $goods)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Goods $goods)
    {
        if($user->role->name == "admin" || $user->permissions()->where('name','delete_goods')->exists()){
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
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Goods $goods)
    {

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Goods  $goods
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Goods $goods)
    {
        //
    }

    public function addGoods(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','add_goods')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function editGoods(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','edit_goods')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function editFareOfCar(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','edit_fare_of_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function confirmGoods(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','confirm_goods')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function cancelConfirmGoods(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','cancel_confirm_goods')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function loadGoodsCar(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','load_goods_on_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function cancelLoadGoodsCar(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','cancel_goods_on_the_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function getGoodsInCar(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','manage_goods_in_car')->orWhere('name','cancel_confirm_goods')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function manageInventory(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','manage_inventory')->orWhere('name','cancel_confirm_goods')->exists()){
            return true;
        }
        else{
            return false;
        }
    }

    public function confirmCollectedMoneyFromCar(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','confirm_collected_money_from_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }
    public function cancelConfirmCollectedMoneyFromCar(User $user){
        if($user->role->name == "admin" || $user->permissions()->where('name','cancel_confirm_collected_money_from_car')->exists()){
            return true;
        }
        else{
            return false;
        }
    }
}
