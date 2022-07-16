<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppConst;
use App\Models\Order;
use Illuminate\Http\Request;

class StatOrderController extends Controller
{
    public function statOrdersDelivered(Request $request){
        $ordersQuery = Order::query();

        if($request->phoneSender){
            $ordersQuery->where('phoneSender', 'like', "%$request->phoneSender%");
        }

        $ordersQuery->where('created_at', '>=' ,$request->start_day)->where('created_at', '<=' ,$request->end_day);

        $ordersQuery->whereDoesntHave('goods', function($query){
            $query->whereNull('car_id');
        })->withSum('goods as total_fare', 'fare')->withSum('goods as total_fare_of_car', 'fareOfCar')
            ->withSum('goods as total_debts', 'collectedMoney')->orderBy('created_at', 'desc');

        $orders = $ordersQuery->get();
        $sum_fare = 0;
        $sum_fare_of_car = 0;
        $sum_debts = 0;
        foreach($orders as $order){
            $sum_fare += $order->total_fare;
            $sum_fare_of_car += $order->total_fare_of_car;
            $sum_debts += $order->total_debts;
        }
        $orders = $ordersQuery->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return response()->json([
            'orders' => $orders,
            'sum_fare' => $sum_fare,
            'sum_fare_of_car' => $sum_fare_of_car,
            'sum_debts' => $sum_debts,
        ]);
    }

    public function statOrdersNotDelivered(Request $request){
        $ordersQuery = Order::query();

        if($request->phoneSender){
            $ordersQuery->where('phoneSender', 'like', "%$request->phoneSender%");
        }

        $ordersQuery->where('created_at', '>=' ,$request->start_day)->where('created_at', '<=' ,$request->end_day);

        $ordersQuery->whereHas('goods', function($query){
            $query->whereNull('car_id')->whereNotNull('confirmDay');
        })->withSum('goods as total_fare', 'fare')->withSum('goods as total_fare_of_car', 'fareOfCar')
            ->withSum('goods as total_debts', 'collectedMoney')->orderBy('created_at', 'desc');

        $orders = $ordersQuery->get();
        $sum_fare = 0;
        $sum_fare_of_car = 0;
        $sum_debts = 0;
        foreach($orders as $order){
            $sum_fare += $order->total_fare;
            $sum_fare_of_car += $order->total_fare_of_car;
            $sum_debts += $order->total_debts;
        }
        $orders = $ordersQuery->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return response()->json([
            'orders' => $orders,
            'sum_fare' => $sum_fare,
            'sum_fare_of_car' => $sum_fare_of_car,
            'sum_debts' => $sum_debts,
        ]);
    }

    public function statOrderNotConfirm(Request $request){
        $ordersQuery = Order::query();

        if($request->phoneSender){
            $ordersQuery->where('phoneSender', 'like', "%$request->phoneSender%");
        }

        $ordersQuery->where('created_at', '>=' ,$request->start_day)->where('created_at', '<=' ,$request->end_day);

        $ordersQuery->whereHas('goods', function($query){
            $query->whereNull('confirmDay')->whereNull('car_id');
        })->withSum('goods as total_fare', 'fare')->withSum('goods as total_fare_of_car', 'fareOfCar')
            ->withSum('goods as total_debts', 'collectedMoney')->orderBy('created_at', 'desc');

        $orders = $ordersQuery->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $orders;
    }
}
