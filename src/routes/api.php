<?php

use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CarRentalController;
use App\Http\Controllers\Admin\CostOfCarController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\GoodsController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\StatOrderController;
use App\Http\Controllers\Admin\StatRevenueController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/change-password', [UserController::class, 'changePassword'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function(){

    //---------------------------- Quản lý nhà xe ------------------------------------------
    Route::resource('/carrental', CarRentalController::class)->names('admin.carrental');

    //-----------------------------Quản lý xe ----------------------------------------------
    Route::resource('/car', CarController::class)->names('admin.car');

    // Lấy tất cả các xe
    Route::get('/get-all-car', [CarController::class, 'getAllCar']);

    //----------------------------- Quản lý đơn hàng ---------------------------------------
    Route::resource('/order', OrderController::class)->names('admin.order');

    // Lấy đơn hàng chưa nhận
    Route::get('/get-order-not-receive', [OrderController::class, 'getOrderNotReceive']);

    // Lấy đơn hàng đã nhận
    Route::get('/get-order-receive', [OrderController::class, 'getOrderReceive']);

    // Lấy đơn hàng đã giao
    Route::get('/get-order-delivered', [OrderController::class, 'getOrderDelivered']);

    //Thêm mặt hàng vào đơn hàng
    Route::post('/add-goods-to-order/{order_id}', [GoodsController::class, 'addGoods']);

    //Xóa mặt hàng trong đơn hàng
    Route::delete('/goods/{goods}/delete', [GoodsController::class, 'deleteGoods']);

    //Lấy mặt hàng của đơn hàng
    Route::get('/get-goods-of-order', [GoodsController::class, 'getGoodsOfOrder']);

    //Lấy đơn hàng có mặt hàng cần tìm
    Route::get('/get-order-contain-goods/{goods_id}', [OrderController::class, 'getOrderContainGoods']);

    //Trả về thông tin mặt hàng
    Route::get('goods/{goods}', [GoodsController::class, 'show']);

    //Sửa thông tin mặt hàng khi mặt hàng chưa được xác nhận
    Route::put('/goods/update-information-goods/{goods}', [GoodsController::class, 'updateInformationGoods']);

    //Sửa cước phí xe khi mặt hàng đã được xếp lên xe
    Route::put('/goods/update-fare-of-car-goods/{goods}', [GoodsController::class, 'updateFareOfCar']);

    //------------------------ Xác nhận mặt hàng nhận được --------------------------------

    //Lấy danh sách mặt hàng chưa được xác nhận
    Route::get('/get-goods-not-confirm', [GoodsController::class, 'getGoodsNotConfirm']);

    //Xác nhận mặt hàng
    Route::put('/confirm-goods/{goods}', [GoodsController::class, 'confirmGoods'])->name('admin.confirmGoods');

    //Hủy xác nhận mặt hàng
    Route::put('/goods/cancel-confirm/{goods}', [GoodsController::class, 'cancelConfirmGoods']);

    //------------------------------------Xếp hàng lên xe -----------------------------------

    //Lấy mặt hàng đã xác nhận nhưng chưa lên xe
    Route::get('/get-goods-not-load-car', [GoodsController::class, 'getGoodsNotLoadCar']);

    //Xếp hàng lên xe
    Route::put('/load-goods-on-car', [GoodsController::class, 'loadGoodsOnTheCar']);

    //Hủy xếp hàng lên xe
    Route::put('/cancel-goods-on-car/{goods}', [GoodsController::class, 'cancelLoadGoodsOnTheCar']);

    //----------------------------------- Quản lý mặt hàng trên xe ---------------------------------
    //Quản lý mặt hàng đã lên các xe
    Route::get('/goods-in-car', [GoodsController::class, 'getGoodsInCar']);

    //------------------------ Quản lý chi phí cầu đường cho xe nhà -------------------------------

    //Lấy danh sách xe của kho
    Route::get('/car-of-ware-house', [CarController::class, 'getCarOfWareHouse']);

    //Lưu chi phí cầu đường cho xe
    Route::post('/cost-of-car/{car}', [CostOfCarController::class, 'store']);

    //Lấy chi phí cầu đường của 1 xe của kho
    Route::get('/cost-of-car/{car}', [CostOfCarController::class, 'getCostsOfCar']);

    //Trả về chi phí của 1 xe của kho trong 1 ngày khi muốn sửa chi phí của xe đó
    Route::get('/cost-of-car/{costOfCar}/edit', [CostOfCarController::class, 'edit']);

    //Update chi phí của 1 xe của kho trong 1 ngày
    Route::put('/cost-of-car/{costOfCar}', [CostOfCarController::class, 'update']);

    //Xóa chi phí của 1 xe của kho trong 1 ngày
    Route::delete('/cost-of-car/{costOfCar}',[CostOfCarController::class, 'delete']);

    //------------------------------ Quản lý hàng tồn kho --------------------------------------

    //Lấy danh sách hàng tồn kho
    Route::get('/get-inventory', [GoodsController::class, 'getInventory']);

    //------------------------------ Quản lý công nợ-----------------------

    //Lấy danh sách mặt hàng được xếp lên xe đó mà chưa thanh toán tiền thu hộ cho kho
    Route::get('/get-goods-not-pay-ware-house/{car_id}', [GoodsController::class, 'getGoodsNotPayWareHouse']);
    //Lấy danh sách mặt hàng được xếp lên xe đó và đã thanh toán tiền thu hộ cho kho
    Route::get('/get-goods-pay-ware-house/{car_id}', [GoodsController::class, 'getGoodsPayWareHouse']);

    //Xác nhận đã nhận tiền từ tài xế cho mặt hàng
    Route::put('/confirm-collected-money-from-car/{goods}', [GoodsController::class, 'confirmCollectedMoneyFromCar']);
    //Hủy nhận đã nhận tiền từ tài xế cho mặt hàng
    Route::put('/cancel_confirm-collected-money-from-car/{goods}', [GoodsController::class, 'cancelConfirmCollectedMoneyFromCar']);

    //--------------------------------- Lấy lịch sử thay đổi của người dùng ----------------------
    Route::get('/histories', [HistoryController::class, 'index']);

    Route::group(['middleware' => ['role:admin'] , 'prefix' => 'admin'], function(){
        //-------------------------- Quản lý nhân viên ------------------------------------
        Route::resource('/employee', EmployeeController::class)->names('admin.employee');

        //Reset mật khẩu nhân viên về mặc định
        Route::put('/employee/reset-password/{user}', [EmployeeController::class, 'resetPassword']);

        //Active/Inactive tài khoản nhân viên
        Route::put('/employee/active-inactive/{user}', [EmployeeController::class, 'activeEmployee']);

        //-------------------Quản lý thanh toán tiền thu hộ cho khách hàng --------------------

        //Lấy danh sách đơn hàng đã thanh toán tiền thu hộ cho khách hàng
        Route::get('/order-confirm-pay', [OrderController::class, 'getOrderConfirmPayCustomer']);

        // Lấy thông tin đơn hàng chưa thanh toán tiền thu hộ cho khách hàng
        Route::get('/order-not-confirm-pay', [OrderController::class, 'getOrderNotConfirmPayCustomer']);

        //Xác nhận thanh toán tiền thu hộ của hóa đơn cho khách hàng
        Route::put('/confirm-pay-customer/{order}', [OrderController::class, 'confirmOrderNotConfirmPayCustomer']);


        //--------------------------------- Thống kê đơn hàng ----------------------------------------

        //Thống kê đơn hàng đã giao
        Route::get('/stat-orders-delivered', [StatOrderController::class, 'statOrdersDelivered']);

        //Thống kê đơn hàng chưa giao
        Route::get('/stat-order-not-delivered', [StatOrderController::class, 'statOrdersNotDelivered']);

        //Thống kê đơn hàng chưa được nhận
        Route::get('/stat-order-not-confirm', [StatOrderController::class, 'statOrderNotConfirm']);

        //--------------------------------- Thống kê doanh thu ----------------------------------------

        //Thống kê doanh thu theo tháng
        Route::get('/stat-revenue-by-month', [StatRevenueController::class, 'statRevenueByMonth']);
        Route::get('/stat-revenue', [StatRevenueController::class, 'statRevenue']);
        Route::get('/stat-order', [StatRevenueController::class, 'statOrder']);
        Route::get('/stat-fare-of-car', [StatRevenueController::class, 'statFareOfCar']);
        Route::get('/stat-costs-of-car', [StatRevenueController::class, 'statCostsOfCar']);

        //-------------------------------- Quản lý quyền truy cập của nhân viên -----------------------

        //Lấy toàn bộ cuả user có thể có
        Route::get('/permissions/{user}', [PermissionController::class, 'index']);

        //Lấy danh sách quyền của 1 user
        Route::get('/permissions-of-user/{user}', [PermissionController::class, 'getPermissionUser']);

        //Gán quyền/ hủy quyền nhân viên
        Route::put('/permissions/{employee_id}/{permission_id}', [PermissionController::class, 'deletePermission']);
    });

});
