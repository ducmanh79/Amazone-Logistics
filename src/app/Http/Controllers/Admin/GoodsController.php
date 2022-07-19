<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChangeDataProcessed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoadGoodsCarRequest;
use App\Http\Requests\Admin\StoreGoodsRequest;
use App\Http\Requests\Admin\UpdateFareOfCarRequest;
use App\Models\AppConst;
use App\Models\Car;
use App\Models\Goods;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function getGoodsOfOrder(Request $request){
        $goods = Goods::where('order_id', $request->order_id)->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $goods;
    }

    public function addGoods(StoreGoodsRequest $request, $order_id){
        $this->authorize('addGoods', Goods::class);
        $goods = new Goods();
        $goods->fill($request->all());
        $goods->order()->associate($order_id);
        $goods->save();
        $goods->load('order');
        $message = $message = "Staff: ".auth('api')->user()->name. " add product ".$goods->name. ", quantity: "
                                    .$goods->quantity. " ".$goods->unit. " in order of ".$goods->order->nameSender. " - ".$goods->order->phoneSender
                                    . " create at ". Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Successfully add product into order'
        ]);
    }

    public function show(Goods $goods){
        return $goods;
    }

    public function updateInformationGoods(StoreGoodsRequest $request, Goods $goods){
        $this->authorize('editGoods', Goods::class);
        if($goods->confirmDay==null){
            $goods->load('order');
            $message = $message = "Staff: ".auth('api')->user()->name. " update product $goods->name, quantity:
                                    $goods->quantity $goods->unit, payment of receiver: $goods->collectedMoney, payment of customer: $goods->fare into $request->name, quantity:
                                    $request->quantity $goods->unit, payment of receiver: $request->collectedMoney, payment of customer: $request->fare in order of ". $goods->order->nameSender. " số điện thoại ". $goods->order->phoneSender.
                                    " tạo ngày ". Carbon::parse($goods->order->created_at)->format('d/m/Y');
            $goods->fill($request->all());
            $goods->save();
            ChangeDataProcessed::dispatch($message);
            return response()->json([
                'success' => 'Successfully update this product',
            ]);
        }
        else{
            return response()->json([
                'error' => 'Unable to update this product',
            ]);
        }
    }

    public function updateFareOfCar(UpdateFareOfCarRequest $request, Goods $goods){
        $this->authorize('editFareOfCar', Goods::class);
        if($goods->loadCarDay == null){
            return response()->json([
                'error' => 'Unable to update product which not load on any truck',
            ]);
        }
        else{
            $message = $message = "Staff: ".auth('api')->user()->name. " updated fare of truck ".$goods->name. ", quantity: "
                                    .$goods->quantity. " ".$goods->unit. "from ".$goods->fareOfCar. " to ". $request->fareOfCar. " in order ".$goods->order->nameSender. ", sdt người gửi là: ".$goods->order->phoneSender
                                    . " created at ". Carbon::parse($goods->order->created_at)->format('d/m/Y');

            $goods->fareOfCar = $request->fareOfCar;
            $goods->save();
            ChangeDataProcessed::dispatch($message);
            return response()->json([
                'message' => 'Successfully update fare of truck',
            ]);
        }
    }

    public function deleteGoods(Goods $goods){
        $this->authorize('delete',$goods ,Goods::class);
        if($goods->confirmDay==null){
            $message = $message = "Staff: ".auth('api')->user()->name. " delete product ".$goods->name. ", quantity: "
                                    .$goods->quantity. " ".$goods->unit. " in order of ".$goods->order->nameSender. ",mobile number of sender: ".$goods->order->phoneSender
                                    . " created at ". Carbon::parse($goods->order->created_at)->format('d/m/Y');
            $goods->delete();
            ChangeDataProcessed::dispatch($message);
            return response()->json([
                'success' => 'Successfully delete product',
            ]);
        }
        else{
            return response()->json([
                'error' => 'Unable to delete product',
            ]);
        }
    }

    public function getGoodsNotConfirm(Request $request){
        $this->authorize('confirmGoods', Goods::class);
        $goodsQuery = Goods::whereNull('confirmDay');
        if($request->key_word){
            $goodsQuery->whereHas('order', function($query) use ($request){
                $query->where('phoneSender', 'like', "%$request->key_word%");
            });
        }
        $goods = $goodsQuery->with('order')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $goods;
    }

    public function confirmGoods(Goods $goods){
        $this->authorize('confirmGoods', Goods::class);
        $goods->confirmDay = Carbon::now()->toDate();
        $goods->user_confirm()->associate(auth('api')->user());
        $goods->save();
        $message = "Staff: ".auth('api')->user()->name. " confirm product ".$goods->name. ", quantity: "
                        .$goods->quantity. " ".$goods->unit. ", mobile number of sender: ". $goods->order->phoneSender. " address: ".$goods->order->addressSender." order created date: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Successfully confirm product',
        ]);
    }

    public function cancelConfirmGoods(Goods $goods){
        $this->authorize('cancelConfirmGoods', Goods::class);
        $goods->confirmDay = null;
        $goods->user_confirm()->dissociate();
        $goods->save();
        $message = "Staff: ".auth('api')->user()->name. " remove product from storage ".$goods->name. ", quantity: "
                        .$goods->quantity. " ".$goods->unit. ", sender mobile number: ". $goods->order->phoneSender. " order created date: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Successfully remove product from storage',
        ]);
    }

    public function getGoodsNotLoadCar(Request $request){
        $this->authorize('loadGoodsCar', Goods::class);
        $goodsQuery = Goods::whereNotNull('confirmDay')->whereNull('loadCarDay');
        if($request->key_word){
            $goodsQuery->whereHas('order', function($query) use ($request){
                $query->where('phoneSender', 'like', "%$request->key_word%");
            });
        }
        $goods = $goodsQuery->with('order')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $goods;
    }

    public function loadGoodsOnTheCar(LoadGoodsCarRequest $request){
        $this->authorize('loadGoodsCar', Goods::class);
        foreach($request->goods as $goodsRequest){
            Goods::whereId($goodsRequest['goods_id'])->update([
                'car_id' => $request->car_id,
                'loadCarDay' => Carbon::now()->toDate(),
                'fareOfCar' => $goodsRequest['fareOfCar'],
                'user_load_car_id' => auth('api')->user()->id,
            ]);
            $goods = Goods::with('car')->find($goodsRequest['goods_id']);
            $message = "Staff: ".auth('api')->user()->name. " load product on truck: ".$goods->car->licensePlate." product ".$goods->name. ", quantity: "
                        .$goods->quantity. " ".$goods->unit. ", sender mobile number: ". $goods->order->phoneSender. " order created date: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
            ChangeDataProcessed::dispatch($message);
        }
        return response()->json([
            'success' => 'Successfully load product on truck',
        ]);
    }

    public function cancelLoadGoodsOnTheCar(Goods $goods){
        $this->authorize('cancelLoadGoodsCar', Goods::class);
        $message = "Staff: ".auth('api')->user()->name. " canceled load product on truck ".$goods->name. ", quantity: "
                        .$goods->quantity. " ".$goods->unit. " on truck ".$goods->car->licensePlate. ", sender mobile number: ". $goods->order->phoneSender. " order created date: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        $goods->car_id = null;
        $goods->loadCarDay = null;
        $goods->fareOfCar = 0;
        $goods->user_load_car_id = null;
        $goods->save();
        return response()->json([
            'success' => 'Successfully unload product on truck',
        ]);
    }

    public function getGoodsInCar(Request $request, Car $car){
        $this->authorize('getGoodsInCar', Goods::class);
        $goodsQuery = Goods::whereNotNull('car_id');
        if($request->day){
            $goodsQuery->where('loadCarDay', $request->day);
        }
        if($request->license_plate){
            $goodsQuery->whereHas('car', function($query) use ($request){
                $query->where('licensePlate', 'like', "%$request->license_plate%");
            });
        }
        $goods = $goodsQuery->with('car')->orderBy('loadCarDay', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $goods;
    }

    public function getInventory(Request $request){
        $this->authorize('manageInventory', Goods::class);
        $goodsQuery = Goods::whereNull('car_id')->whereNotNull('confirmDay');

        if($request->date){
            $goodsQuery->whereDate('confirmDay', $request->date);
        }

        if($request->key_word){
            $goodsQuery->whereHas('order', function($query) use ($request){
                $query->where('phoneSender', 'like', "%$request->key_word%");
            });
        }

        $goods = $goodsQuery->with('order')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $goods;
    }

    public function getGoodsNotPayWareHouse(Request $request, $car_id){
        $this->authorize('confirmCollectedMoneyFromCar', Goods::class);
        $goodsQuery = Goods::where('collectedMoney', '<>', 0)->where('car_id', $car_id)->whereNotNull('confirmDay')->whereNull('confirmCarPayWareHouseDay');

        if($request->date){
            $goodsQuery->whereDate('loadCarDay', $request->date);
        }

        $goods = $goodsQuery->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $goods;
    }

    public function getGoodsPayWareHouse(Request $request, $car_id){
        $this->authorize('cancelConfirmCollectedMoneyFromCar', Goods::class);
        $goodsQuery = Goods::where('collectedMoney', '<>', 0)->where('car_id', $car_id)->whereNotNull('confirmDay')->whereNotNull('confirmCarPayWareHouseDay');

        if($request->date){
            $goodsQuery->whereDate('confirmCarPayWareHouseDay', $request->date);
        }

        $goods = $goodsQuery->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $goods;
    }

    public function confirmCollectedMoneyFromCar(Goods $goods){
        $this->authorize('confirmCollectedMoneyFromCar', Goods::class);
        $goods->confirmCarPayWareHouseDay = Carbon::now()->toDate();
        $goods->save();
        $goods->load('order');
        $message = "Staff: ".auth('api')->user()->name. "received payment of receiver for: ".$goods->name." ".$goods->unit. "of sender: ".
                        $goods->order->nameSender." - ".$goods->order->phoneSender. " amount: ".$goods->collectedMoney. ", sender mobile number: ". $goods->order->phoneSender. " order created date: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Successfully receive payment of receiver for this product',
        ]);
    }
    public function cancelConfirmCollectedMoneyFromCar(Goods $goods){
        $this->authorize('cancelConfirmCollectedMoneyFromCar', Goods::class);
        $goods->confirmCarPayWareHouseDay = null;
        $goods->save();
        $goods->load('order');
        $message = "Staff: ".auth('api')->user()->name. "cancel receiving payment of receiver: ".$goods->name." ".$goods->unit. "of sender: ".
                        $goods->order->nameSender." - ".$goods->order->phoneSender. " amount: ".$goods->collectedMoney. ", sender mobile number: ". $goods->order->phoneSender. " order created at: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Successfully cancel receive payment of receiver',
        ]);
    }
}
