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
        $message = "Staff: ".auth('api')->user()->name . " added cost of truck ". $request->name. " of truck with license plate ".
                    $car->licensePlate. " date ".Carbon::createFromFormat('Y-m-d', $costOfCar->date)->format('d/m/Y'). " with fee ".$costOfCar->cost;

        ChangeDataProcessed::dispatch($message);

        return response()->json([
            'success' => 'Successfully update cost of truck',
        ]);
    }

    public function edit(CostOfCar $costOfCar)
    {
        return $costOfCar;
    }

    public function update(StoreCostOfCarRequest $request, CostOfCar $costOfCar)
    {
        $this->authorize('updateCostOfCar', CostOfCar::class);
        $message = "Staff: ".auth('api')->user()->name . " updated cost of truck ". $costOfCar->name. " of truck with license plate ".
                    $costOfCar->car->licensePlate. " date ".Carbon::createFromFormat('Y-m-d', $costOfCar->date)->format('d/m/Y').
                    " with fee: " .$costOfCar->cost." into : $request->name with fee of: ".$costOfCar->cost;
        $costOfCar->fill($request->all());
        $costOfCar->user()->associate(auth('api')->user()->id);
        $costOfCar->save();
        $costOfCar->load('car');

        ChangeDataProcessed::dispatch($message);
        return response()->json([
            'success' => 'Successfully update cost of truck',
        ]);
    }

    public function delete(CostOfCar $costOfCar){
        $this->authorize('deleteCosOfCar', CostOfCar::class);
        $costOfCar->load('car');
        $message = "Staff: ".auth('api')->user()->name . " delted cost of truck with license plate ".
                    $costOfCar->car->licensePlate. " date ".Carbon::createFromFormat('Y-m-d', $costOfCar->date)->format('d/m/Y').
                    " with fee of: ".$costOfCar->cost;
        ChangeDataProcessed::dispatch($message);
        $costOfCar->delete();
        return response()->json([
            'success' => 'Sucessfully delete fee',
        ]);
    }
}
