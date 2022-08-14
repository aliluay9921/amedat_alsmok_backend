<?php

use App\Http\Controllers\AccountingController;
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

    route::middleware('saleCategoryAdmin')->group(function () {
        route::post("add_sale", [salesCategoryController::class, 'addSale']);
        route::put("update_sale_category", [salesCategoryController::class, 'updateSaleCategory']);
        route::put("sending_to_processing", [salesCategoryController::class, 'sendingToProcessing']);
        route::delete("delete_sale_category", [salesCategoryController::class, 'deleteSaleCategory']);
    });

    route::middleware('processing')->group(function () {
        route::get("get_invoicemnts", [InvoicmentController::class, 'getInvoicemnt']);
        route::post("add_to_done", [ProcessingController::class, 'addToDone']);
        route::post("add_invoicement", [InvoicmentController::class, 'addInvoicemnt']);
        route::put("done_invoice", [salesCategoryController::class, 'doneInvoice']);
    });
    route::middleware('AccountingAdmin')->group(function () {
        route::get("get_representatives", [AccountingController::class, 'getRepresentatives']);
        route::post("add_representative", [AccountingController::class, 'addRepresentative']);
    });
});