<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SalesmanController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\PaymentController;
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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(["namespace"=>"Api"], function(){

    Route::group(['middleware' => 'auth:api'], function(){
        /*Salesman*/
        Route::get('salesmanData', [SalesmanController::class, 'getData']);

        /*Salesman Sale*/
        Route::post('salesmanSalesStore', [SaleController::class, 'Store']);
        Route::get('salesmanSalesData', [SaleController::class, 'getData']);

        /*Salesman Payment*/
        Route::post('salesmanPaymentsStore', [PaymentController::class, 'Store']);
        Route::get('salesmanPaymentsData', [PaymentController::class, 'getData']);

        /*Salesman Attendance*/
        Route::get('salesmanAttendanceData', [SalesmanController::class, 'salesmanAttendanceData']);

        /*Salesman Route*/
        Route::get('salesmanRouteData', [SalesmanController::class, 'salesmanRouteData']);

        /*Customer*/
        Route::post('customerGiftUpdate/{id}', [CustomerController::class, 'giftUpdate']);
        Route::post('customerStore', [CustomerController::class, 'Store']);
        Route::get('customerData', [CustomerController::class, 'getData']);
        Route::get('customerGiftData', [CustomerController::class, 'customerGiftData']);

        /*Product*/
        Route::get('productData', [ProductController::class, 'getData']);

        /*User*/
        Route::get('userData', [UserController::class, 'getData']);
    });
    Route::post('login', [\App\Http\Controllers\Api\LoginController::class, 'login']);
});
