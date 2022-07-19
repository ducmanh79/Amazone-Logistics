<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChangeDataProcessed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ConfirmPayCustomer;
use App\Http\Requests\Admin\OrderRequest;
use App\Models\AppConst;
use App\Models\Goods;
use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->authorizeResource(Order::class, 'order');
    }
    public function index(Request $request)
    {
        $ordersQuery = Order::where('created_at', '>=', $request->start_day)->where('created_at', '<=', $request->end_day);

        if($request->phoneSender){
            $ordersQuery->where('phoneSender', 'like', "$request->phoneSender");
        }
        $orders = $ordersQuery->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $orders;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->fill($request->all());
            $order->user()->associate(auth('api')->user()->id);
            $order->save();
            $message = "Staff: ".auth('api')->user()->name. " created order for ". $order->nameSender. ", address: ".
                        $order->addressSender. ", mobile number: ". $order->phoneSender. " send to ". $order->nameReceiver. ", address: ".
                        $order->addressReceiver. ", mobile number: ". $order->phoneReceiver. " date ".Carbon::parse($order->created_at)->format('d/m/Y');

            ChangeDataProcessed::dispatch($message);

            if($request->goods){
                foreach ($request->goods as $goodsRequest){
                    $goods = new Goods();
                    $goods->fill($goodsRequest);
                    $goods->order()->associate($order->id);
                    $goods->save();
                }
            }

            DB::commit();
            return $order;
        }
        catch(Exception $e){
            DB::rollback();

            return response()->json([
                'error' => $e,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $order->load('goods.car');
        return $order;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $order->load('goods');
        return $order;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $order)
    {
        $message = "Staff: ".auth('api')->user()->name. " updated order of ". $order->nameSender. ", address: ".
                        $order->addressSender. ", mobile number: ". $order->phoneSender. " send to ". $order->nameReceiver. ", address: ".
                        $order->addressReceiver. ", mobile number: ". $order->phoneReceiver. " date ".Carbon::parse($order->created_at)->format('d/m/Y').
                        " to sender: ".$request->nameSender. ", address: ". $request->addressSender. ", mobile number: ". $request->phoneSender. " send to ". $request->nameReceiver. ", address: ".
                        $request->addressReceiver. ", mobile number: ". $request->phoneReceiver;
        $order->fill($request->all());
        $order->user()->associate(auth('api')->user()->id);
        $order->save();
        ChangeDataProcessed::dispatch($message);
        return $order->load('goods');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order = Order::whereDoesntHave('goods', function ($query){
            $query->whereNotNull('confirmDay');
        })->find($order->id);
        if($order){
            $message = "Staff: ".auth('api')->user()->name. " delete order for ". $order->nameSender. ", address: ".
                        $order->addressSender. ", mobile number: ". $order->phoneSender. " send to". $order->nameReceiver. " address: ".
                        $order->addressReceiver. ", mobile number: ". $order->phoneReceiver. " date ".Carbon::parse($order->created_at)->format('d/m/Y');;
            ChangeDataProcessed::dispatch($message);
            $order->delete();
            return response()->json([
                'success' => 'Sucessfully delete order',
            ]);
        }
        else{
            return response()->json([
                'error' => 'Unable to delete order',
            ]);
        }
    }

    public function getOrderNotReceive(Request $request){
        $this->authorize('getOrderNotReceive', Order::class);
        $ordersQuery = Order::WhereDoesntHave('goods')->orwhereHas('goods', function($query){
            $query->whereNull('confirmDay');
        });
        if($request->date){
            $ordersQuery->whereDate('created_at', $request->date);
        }
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $orders;
    }

    public function getOrderReceive(Request $request){
        $this->authorize('getOrderReceive', Order::class);
        $ordersQuery = Order::whereHas('goods', function($query){
            $query->whereNotNull('confirmDay')->whereNull('loadCarDay');
        });
        if($request->date){
            $ordersQuery->whereDate('created_at', $request->date);
        }
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $orders;
    }

    public function getOrderDelivered(Request $request){
        $this->authorize('getOrderDelivered', Order::class);
        $ordersQuery = Order::whereHas('goods', function($query){
            $query->whereNotNull('loadCarDay');
        });
        if($request->date){
            $ordersQuery->whereDate('created_at', $request->date);
        }
        $orders = $ordersQuery->orderBy('created_at', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $orders;
    }

    public function getOrderConfirmPayCustomer(Request $request){
        $this->authorize('confirmPayCustomer', Order::class);
        $ordersQuery = Order::whereHas('goods', function($query){
                                $query->where('collectedMoney', '>', 0);
                            })
                            ->whereNotNull('confirmPayCustomerDay')->whereDoesntHave('goods', function ($query){
                                $query->whereNull('confirmDay');
                            });
        if($request->phoneSender){
            $ordersQuery->where('phoneSender', 'like', "%$request->phoneSender%");
        }
        $orders = $ordersQuery->withSum('goods as total_collected_money', 'collectedMoney')
                        ->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);

        return $orders;
    }

    public function getOrderNotConfirmPayCustomer(Request $request){
        $this->authorize('confirmPayCustomer', Order::class);
        $ordersQuery = Order::whereHas('goods', function($query){
                                $query->where('collectedMoney', '>', 0);
                            })->whereNull('confirmPayCustomerDay')->whereDoesntHave('goods', function ($query){
                                $query->whereNull('confirmDay');
                            });
        if($request->phoneSender){
            $ordersQuery->where('phoneSender', 'like', "%$request->phoneSender%");
        }

        $orders = $ordersQuery->withSum('goods as total_collected_money', 'collectedMoney')
                        ->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);

        return $orders;
    }

    public function confirmOrderNotConfirmPayCustomer(Order $order){
        $this->authorize('confirmPayCustomer', Order::class);
        if($order->confirmPayCustomerDay == null){
            $order->confirmPayCustomerDay = Carbon::now()->toDate();
        }
        else{
            $order->confirmPayCustomerDay = null;
        }
        $order->save();
        return response()->json([
            'success' => 'Success',
        ]);
    }

    public function getOrderContainGoods($goods_id){
        $goods = Goods::with('order.goods')->find($goods_id);
        return $goods->order;
    }

}
