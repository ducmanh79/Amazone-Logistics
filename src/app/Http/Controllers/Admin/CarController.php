<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChangeDataProcessed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCarRequest;
use App\Http\Requests\Admin\UpdateCarRequest;
use App\Models\AppConst;
use App\Models\Car;
use Illuminate\Http\Request;
class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->authorizeResource(Car::class, 'car');
    }
    public function index(Request $request)
    {
        $carsQuery = Car::where('carrental_id', '=', $request->carrental_id);
        if($request->key_word){
            $carsQuery->where('licensePlate', 'like', "%$request->key_word%");
        }
        $cars = $carsQuery->orderBy('created_at', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $cars;
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
    public function store(StoreCarRequest $request)
    {
        $car = new Car();
        $car->fill($request->all());
        $car->carrental()->associate($request->carrental_id);
        $car->save();
        $message = "Nhân viên: ".auth('api')->user()->name . " đã thêm xe biển số: ".
                    $car->licensePlate. ", số điện thoại: ". $car->phoneNumber. ", lộ trình: ". $car->route. " ở nhà xe: ". $car->carrental->name;

        ChangeDataProcessed::dispatch($message);
        return $car;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        return $car;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        return $car;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        $message = "Nhân viên: ".auth('api')->user()->name . " đã sửa xe biển số: ".
                    $car->licensePlate. ", số điện thoại: ". $car->phoneNumber. ", lộ trình: ". $car->route. " thành ".$request->licensePlate.
                    ", số điện thoại: ". $request->phoneNumber. ", lộ trình: ". $request->route. " ở nhà xe: ". $car->carrental->name;

        $car->fill($request->all());
        ChangeDataProcessed::dispatch($message);
        $car->save();
        return $car;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {

    }
    public function getCarOfWareHouse(Request $request){
        $carsQuery = Car::whereHas('carrental', function($query){
            $query->where('isHome', true);
        });
        if($request->key_word){
            $carsQuery->where('licensePlate', 'like', "%$request->key_word%");
        }
        $cars = $carsQuery->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $cars;
    }

    public function getAllCar(Request $request){
        $this->authorize('getAllCar',Car::class);
        $carsQuery = Car::query();
        if($request->licensePlate){
            $carsQuery->where('licensePlate', 'like', "%$request->licensePlate%");
        }
        $cars = $carsQuery->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);
        return $cars;
    }
}
