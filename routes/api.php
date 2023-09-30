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
    route::get("get_sales_to_representatives", [salesCategoryController::class, 'getSalesToRepresentatives']);
    route::get("get_representatives", [AccountingController::class, 'getRepresentatives']);
    route::get("get_drivers", [AccountingController::class, "getDrivers"]);
    route::get("get_cars", [AccountingController::class, "getCars"]);
    route::get("get_users", [AccountingController::class, "getUsers"]);

    route::middleware('saleCategoryAdmin')->group(function () {
        route::post("add_sale", [salesCategoryController::class, 'addSale']);
        route::put("update_sale_category", [salesCategoryController::class, 'updateSaleCategory']);
        route::put("sending_to_processing", [salesCategoryController::class, 'sendingToProcessing']);
        route::put("go_bump", [salesCategoryController::class, 'goBump']);
        route::put("add_note", [salesCategoryController::class, 'addNote']);
        route::delete("delete_sale_category", [salesCategoryController::class, 'deleteSaleCategory']);
    });

    route::middleware('processing')->group(function () {
        route::get("get_invoicemnts", [InvoicmentController::class, 'getInvoicemnt']);
        route::post("add_to_done", [ProcessingController::class, 'addToDone']);
        route::post("add_invoicement", [InvoicmentController::class, 'addInvoicemnt']);
        route::put("done_invoice", [salesCategoryController::class, 'doneInvoice']);
    });
    route::middleware('AccountingAdmin')->group(function () {
        route::post("add_representative", [AccountingController::class, 'addRepresentative']);
        route::post("add_driver", [AccountingController::class, 'addDriver']);
        route::post("add_car", [AccountingController::class, "addCar"]);
        route::post("show_password", [AccountingController::class, 'showPassword']);
        route::put("reset_password", [AccountingController::class, 'resetPassword']);
        route::put("make_paid", [AccountingController::class, 'makePaid']);

        route::put("update_represenivet", [AccountingController::class, 'updateRepresentive']);
        route::delete("delete_driver", [AccountingController::class, "deleteDriver"]);
        route::delete("delete_representive", [AccountingController::class, 'deleteRepresentive']);
        route::delete("delete_car", [AccountingController::class, "deleteCar"]);
    });
});
