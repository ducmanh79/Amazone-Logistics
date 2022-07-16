<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChangeDataProcessed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCostOfCarRequest;
use App\Models\AppConst;
use App\Models\Car;
use App\Models\CostOfCar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CostOfCarController extends Controller
{
    public function getCostsOfCar(Request $request, Car $car){
        $this->authorize('viewAny', CostOfCar::class);
        $costsOfCarQuery = CostOfCar::where('car_id', $car->id);

        if($request->date){
            $costsOfCarQuery->where('date', $request->date);
        }

        $costsOfCar = $costsOfCarQuery->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        $sumCostsOfCar = $costsOfCarQuery->sum('cost');
        return response()->json([
            'costOfCar' => $costsOfCar,
            'sum' => $sumCostsOfCar
        ]);
    }

    public function store(StoreCostOfCarRequest $request, Car $car)
    {
        $this->authorize('create', CostOfCar::class);
        $costOfCar = new CostOfCar();
        $costOfCar->fill($request->all());
        $costOfCar->car()->associate($car->id);
        $costOfCar->user()->associate(auth('api')->user()->id);
        $costOfCar->save();
        $message = "Nhân viên: ".auth('api')->user()->name . " đã thêm chi phí ". $request->name. " của xe biển kiểm soát ".
                    $car->licensePlate. " ngày ".Carbon::createFromFormat('Y-m-d', $costOfCar->date)->format('d/m/Y'). " với chi phí ".$costOfCar->cost;

        ChangeDataProcessed::dispatch($message);

        return response()->json([
            'success' => 'Cập nhật chi phí của xe thành công',
        ]);
    }

    public function edit(CostOfCar $costOfCar)
    {
        return $costOfCar;
    }

    public function update(StoreCostOfCarRequest $request, CostOfCar $costOfCar)
    {
        $this->authorize('updateCostOfCar', CostOfCar::class);
        $message = "Nhân viên: ".auth('api')->user()->name . " đã cập nhật chi phí ". $costOfCar->name. " của xe biển kiểm soát ".
                    $costOfCar->car->licensePlate. " ngày ".Carbon::createFromFormat('Y-m-d', $costOfCar->date)->format('d/m/Y').
                    " với chi phí: " .$costOfCar->cost." thành chi phí : $request->name với chi phí là: ".$costOfCar->cost;
        $costOfCar->fill($request->all());
        $costOfCar->user()->associate(auth('api')->user()->id);
        $costOfCar->save();
        $costOfCar->load('car');

        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Cập nhật chi phí thành công',
        ]);
    }

    public function delete(CostOfCar $costOfCar){
        $this->authorize('deleteCosOfCar', CostOfCar::class);
        $costOfCar->load('car');
        $message = "Nhân viên: ".auth('api')->user()->name . " đã xóa chi phí của xe biển kiểm soát ".
                    $costOfCar->car->licensePlate. " ngày ".Carbon::createFromFormat('Y-m-d', $costOfCar->date)->format('d/m/Y').
                    " vói chi phí: ".$costOfCar->cost;
        ChangeDataProcessed::dispatch($message);
        $costOfCar->delete();
        return response()->json([
            'success' => 'Xóa chi phí thành công',
        ]);
    }
}
