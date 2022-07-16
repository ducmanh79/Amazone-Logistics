<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'nameSender',
        'addressSender',
        'phoneSender',
        'nameReceiver',
        'addressReceiver',
        'phoneReceiver',
        'confirmPayCustomerDay'
    ];

    public function goods(){
        return $this->hasMany(Goods::class, 'order_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
