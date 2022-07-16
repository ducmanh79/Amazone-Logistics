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
        $message = $message = "Nhân viên: ".auth('api')->user()->name. " thêm mặt hàng ".$goods->name. ", số lượng: "
                                    .$goods->quantity. " ".$goods->unit. " trong đơn hàng của ".$goods->order->nameSender. " - ".$goods->order->phoneSender
                                    . " tạo ngày ". Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Thêm mặt hàng vào đơn hàng thành công'
        ]);
    }

    public function show(Goods $goods){
        return $goods;
    }

    public function updateInformationGoods(StoreGoodsRequest $request, Goods $goods){
        $this->authorize('editGoods', Goods::class);
        if($goods->confirmDay==null){
            $goods->load('order');
            $message = $message = "Nhân viên: ".auth('api')->user()->name. " sửa mặt hàng $goods->name, số lượng:
                                    $goods->quantity $goods->unit, tiền thu hộ: $goods->collectedMoney, tiền thu khách là: $goods->fare thành $request->name, số lượng:
                                    $request->quantity $goods->unit, tiền thu hộ: $request->collectedMoney, tiền thu khách là: $request->fare trong đơn hàng của ". $goods->order->nameSender. " số điện thoại ". $goods->order->phoneSender.
                                    " tạo ngày ". Carbon::parse($goods->order->created_at)->format('d/m/Y');
            $goods->fill($request->all());
            $goods->save();
            ChangeDataProcessed::dispatch($message);
            return response()->json([
                'success' => 'Sửa mặt hàng thành công',
            ]);
        }
        else{
            return response()->json([
                'error' => 'Không thể sửa mặt hàng này',
            ]);
        }
    }

    public function updateFareOfCar(UpdateFareOfCarRequest $request, Goods $goods){
        $this->authorize('editFareOfCar', Goods::class);
        if($goods->loadCarDay == null){
            return response()->json([
                'error' => 'Không thể cập nhật chi phí mặt hàng chưa xếp lên xe',
            ]);
        }
        else{
            $message = $message = "Nhân viên: ".auth('api')->user()->name. " sửa chi phí lên xe của mặt hàng ".$goods->name. ", số lượng: "
                                    .$goods->quantity. " ".$goods->unit. "từ ".$goods->fareOfCar. " thành ". $request->fareOfCar. " trong đơn hàng của ".$goods->order->nameSender. ", sdt người gửi là: ".$goods->order->phoneSender
                                    . " tạo ngày ". Carbon::parse($goods->order->created_at)->format('d/m/Y');

            $goods->fareOfCar = $request->fareOfCar;
            $goods->save();
            ChangeDataProcessed::dispatch($message);
            return response()->json([
                'message' => 'Cập nhật chi phí lên xe thành công',
            ]);
        }
    }

    public function deleteGoods(Goods $goods){
        $this->authorize('delete',$goods ,Goods::class);
        if($goods->confirmDay==null){
            $message = $message = "Nhân viên: ".auth('api')->user()->name. " xóa mặt hàng ".$goods->name. ", số lượng: "
                                    .$goods->quantity. " ".$goods->unit. " trong đơn hàng của ".$goods->order->nameSender. ",sdt người gửi là: ".$goods->order->phoneSender
                                    . " tạo ngày ". Carbon::parse($goods->order->created_at)->format('d/m/Y');
            $goods->delete();
            ChangeDataProcessed::dispatch($message);
            return response()->json([
                'success' => 'Xóa mặt hàng thành công',
            ]);
        }
        else{
            return response()->json([
                'error' => 'Không thể xóa mặt hàng này',
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
        $message = "Nhân viên: ".auth('api')->user()->name. " nhập kho mặt hàng ".$goods->name. ", số lượng: "
                        .$goods->quantity. " ".$goods->unit. ", số điện thoại người gửi là: ". $goods->order->phoneSender. " địa chỉ: ".$goods->order->addressSender." ngày tạo đơn là: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Xác nhận mặt hàng thành công',
        ]);
    }

    public function cancelConfirmGoods(Goods $goods){
        $this->authorize('cancelConfirmGoods', Goods::class);
        $goods->confirmDay = null;
        $goods->user_confirm()->dissociate();
        $goods->save();
        $message = "Nhân viên: ".auth('api')->user()->name. " hủy kho mặt hàng ".$goods->name. ", số lượng: "
                        .$goods->quantity. " ".$goods->unit. ", số điện thoại người gửi là: ". $goods->order->phoneSender. " ngày tạo đơn là: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Hủy xác nhận mặt hàng thành công',
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
            $message = "Nhân viên: ".auth('api')->user()->name. " xếp lên xe: ".$goods->car->licensePlate." mặt hàng ".$goods->name. ", số lượng: "
                        .$goods->quantity. " ".$goods->unit. ", số điện thoại người gửi là: ". $goods->order->phoneSender. " ngày tạo đơn là: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
            ChangeDataProcessed::dispatch($message);
        }
        return response()->json([
            'success' => 'Xếp hàng lên xe thành công',
        ]);
    }

    public function cancelLoadGoodsOnTheCar(Goods $goods){
        $this->authorize('cancelLoadGoodsCar', Goods::class);
        $message = "Nhân viên: ".auth('api')->user()->name. " đã hủy mặt hàng ".$goods->name. ", số lượng: "
                        .$goods->quantity. " ".$goods->unit. " trên xe ".$goods->car->licensePlate. ", số điện thoại người gửi là: ". $goods->order->phoneSender. " ngày tạo đơn là: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        $goods->car_id = null;
        $goods->loadCarDay = null;
        $goods->fareOfCar = 0;
        $goods->user_load_car_id = null;
        $goods->save();
        return response()->json([
            'success' => 'Hủy xếp hàng lên xe thành công',
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
        $message = "Nhân viên: ".auth('api')->user()->name. "nhận tiền thu hộ của mặt hàng: ".$goods->name." ".$goods->unit. "của người gửi: ".
                        $goods->order->nameSender." - ".$goods->order->phoneSender. " số tiền: ".$goods->collectedMoney. ", số điện thoại người gửi là: ". $goods->order->phoneSender. " ngày tạo đơn là: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Xác nhận thu phí thu hộ cho đơn hàng thành công',
        ]);
    }
    public function cancelConfirmCollectedMoneyFromCar(Goods $goods){
        $this->authorize('cancelConfirmCollectedMoneyFromCar', Goods::class);
        $goods->confirmCarPayWareHouseDay = null;
        $goods->save();
        $goods->load('order');
        $message = "Nhân viên: ".auth('api')->user()->name. "hủy nhận tiền thu hộ của mặt hàng: ".$goods->name." ".$goods->unit. "của người gửi: ".
                        $goods->order->nameSender." - ".$goods->order->phoneSender. " số tiền: ".$goods->collectedMoney. ", số điện thoại người gửi là: ". $goods->order->phoneSender. " ngày tạo đơn là: ".
                        Carbon::parse($goods->order->created_at)->format('d/m/Y');
        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Hủy xác nhận thu phí thu hộ cho đơn hàng thành công',
        ]);
    }
}
