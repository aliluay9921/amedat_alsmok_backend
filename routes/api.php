<?php

use App\Http\Controllers\InvoicmentController;
use App\Http\Controllers\ProcessingController;
use App\Http\Controllers\salesCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

route::post("login", [UserController::class, "login"]);

route::middleware("auth:api")->group(function () {

    route::get("get_sale", [salesCategoryController::class, 'getSale']);
    route::get("get_sales_processing", [salesCategoryController::class, 'getSalesProcessing']);
    route::get("get_sales_done", [salesCategoryController::class, 'getSalesDone']);
    route::get("get_invoicemnts", [InvoicmentController::class, 'getInvoicemnt']);
    route::get("get_done_invoice", [salesCategoryController::class, 'getDoneInvoice']);



    route::post("add_to_done", [ProcessingController::class, 'addToDone']);
    route::post("add_sale", [salesCategoryController::class, 'addSale']);
    route::post("add_invoicement", [InvoicmentController::class, 'addInvoicemnt']);


    route::put("sending_to_processing", [salesCategoryController::class, 'sendingToProcessing']);
    route::put("done_invoice", [salesCategoryController::class, 'doneInvoice']);
    route::put("update_sale_category", [salesCategoryController::class, 'updateSaleCategory']);


    route::delete("delete_sale_category", [salesCategoryController::class, 'deleteSaleCategory']);
});