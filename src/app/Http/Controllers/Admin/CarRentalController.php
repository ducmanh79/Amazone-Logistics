<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChangeDataProcessed;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCarRentalRequest;
use App\Models\AppConst;
use App\Models\CarRental;
use Illuminate\Http\Request;

class CarRentalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->authorizeResource(CarRental::class, 'carrental');
    }
    public function index(Request $request)
    {
        $carRentalsQuery = CarRental::query();

        if($request->key_word){
            $carRentalsQuery->where('name', 'like' , "%$request->key_word%");
        }

        $carRentals = $carRentalsQuery->orderBy('created_at', 'desc')->paginate($request->per_page ?? AppConst::DEFAULT_PER_PAGE);

        return $carRentals;
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
    public function store(StoreCarRentalRequest $request)
    {
        $carRental = new CarRental();
        $carRental->fill($request->all());
        $carRental->save();
        $message = "Nhân viên: ".auth('api')->user()->name . " đã tạo nhà xe ".
                    $carRental->name. ", số điện thoại: ". $carRental->phoneNumber. ", loại nhà xe: ". $this->typeCarRental($request->isHome);

        ChangeDataProcessed::dispatch($message);
        return $carRental;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CarRental  $carRental
     * @return \Illuminate\Http\Response
     */
    public function show(CarRental $carrental)
    {

        return $carrental;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarRental  $carRental
     * @return \Illuminate\Http\Response
     */
    public function edit(CarRental $carrental)
    {
        return $carrental;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarRental  $carRental
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCarRentalRequest $request, CarRental $carrental)
    {
        $message = "Nhân viên: ".auth('api')->user()->name . " đã sửa nhà xe ".
                    $carrental->name. ", số điện thoại: ". $carrental->phoneNumber. ", loại nhà xe: ". $this->typeCarRental($carrental->isHome) ." thành ".
                    $request->name. ", số điện thoại: ". $request->phoneNumber. ", loại nhà xe: ".$this->typeCarRental($request->isHome);

        $carrental->fill($request->all());
        ChangeDataProcessed::dispatch($message);
        $carrental->save();
        return $carrental;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarRental  $carRental
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarRental $carrental)
    {

    }
    public function typeCarRental($isHome){
        if($isHome == 1){
            return 'nhà xe của kho';
        }
        else{
            return 'nhà xe ngoài';
        }
    }

}
