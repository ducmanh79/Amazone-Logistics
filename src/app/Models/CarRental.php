<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route as FacadesRoute;

class CarRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phoneNumber',
        'isHome',
    ];

    protected $appends = ['totalDebt'];

    public function cars(){
        return $this->hasMany(Car::class, 'carrental_id');
    }

    public function getTotalDebtAttribute(){
        return $this->cars()->get()->reduce(function($total, $car){
            return $total += $car->totalDebt;
        });
     }

    public function debts(){
        return $this->cars();
    }
}
