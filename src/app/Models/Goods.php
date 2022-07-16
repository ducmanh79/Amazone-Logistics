<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'collectedMoney',
        'fare',
        'order_id',
    ];

    protected $appends = ['status'];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function car(){
        return $this->belongsTo(Car::class);
    }

    public function user_confirm(){
        return $this->belongsTo(User::class);
    }

    public function user_load_car(){
        return $this->belongsTo(User::class);
    }
    public function getStatusAttribute(){
        if($this->confirmDay == null){
            return 'not_received';
        }
        elseif($this->confirmDay!=null && $this->car_id == null){
            return 'received';
        }
        else{
            return 'delivered';
        }
    }
}
