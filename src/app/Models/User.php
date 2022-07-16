<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'phone',
        'name',
        'password',
        'salary',
        'idNumber',
        'address',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function role(){
        return $this->belongsTo('App\Models\Role');
    }

    public function orders(){
        return $this->hasMany(Order::class, 'user_id');
    }

    public function goods_confirm(){
        return $this->hasMany(Goods::class, 'user_confirm_id');
    }

    public function goods_load_car(){
        return $this->hasMany(Goods::class, 'user_load_car_id');
    }

    public function costs_of_car(){
        return $this->hasMany(CostOfCar::class, 'user_id');
    }

    public function histories(){
        return $this->hasMany(History::class, 'user_id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'user_permission', 'user_id', 'permission_id');
    }
}
