<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'licensePlate',
        'phoneNumber',
        'route',
    ];

    protected $appends = ['totalDebt'];

    public function carrental(){
        return $this->belongsTo(CarRental::class);
    }

    public function goods(){
        return $this->hasMany(Goods::class, 'car_id');
    }

    public function getTotalDebtAttribute(){
       return $this->goods()->whereNotNull('loadCarDay')->whereNull('confirmCarPayWareHouseDay')->sum('collectedMoney');
    }

    public function costs(){
        return $this->hasMany(CostOfCar::class, 'car_id');
    }
}
