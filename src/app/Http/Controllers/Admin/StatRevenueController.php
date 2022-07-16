<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StatRevenueRequest;
use App\Models\AppConst;
use App\Models\CostOfCar;
use App\Models\Goods;
use App\Models\Order;

class StatRevenueController extends Controller
{
    public function statRevenue(StatRevenueRequest $request){
        $orders = Order::with(['goods' => function ($query) use ($request){
                                $query->where('confirmDay', '>=' ,$request->start_day)->where('confirmDay', '<=' ,$request->end_day);
                            }])->get();
        $total_fare = 0; $total_fare_of_car=0;
        foreach ($orders as $order) {
            foreach ($order->goods as $goods) {
                $total_fare += $goods->fare;
                $total_fare_of_car += $goods->fareOfCar;
            }
        }

        $total_cost_of_car = CostOfCar::where('date', '>=' ,$request->start_day)->where('date', '<=' ,$request->end_day)
                            ->sum('cost');

        return response()->json([
            'total_fare' => $total_fare,
            'total_fare_of_car' => $total_fare_of_car,
            'total_cost_of_car' => $total_cost_of_car,
            'total_debts' => $total_fare - $total_fare_of_car - $total_cost_of_car,
        ]);
    }

    public function statOrder(StatRevenueRequest $request){
        $orders = Order::whereHas('goods', function ($query) use ($request){
                        $query->where('confirmDay', '>=' ,$request->start_day)->where('confirmDay', '<=' ,$request->end_day);
                    })->withSum('goods as total_fare', 'fare')->orderBy('created_at', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $orders;
    }

    public function statFareOfCar(StatRevenueRequest $request){
        $goods = Goods::where('loadCarDay', '>=' ,$request->start_day)->where('loadCarDay', '<=' ,$request->end_day)->with(['order', 'car'])
                        ->orderBy('created_at', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $goods;
    }

    public function statCostsOfCar(StatRevenueRequest $request){
        $costsOfCars = CostOfCar::where('date', '>=' ,$request->start_day)->where('date', '<=' ,$request->end_day)->with('car')->orderBy('created_at', 'desc')
                                ->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $costsOfCars;
    }

}
