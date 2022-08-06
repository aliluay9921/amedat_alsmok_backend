<?php

namespace App\Http\Controllers;

use App\Models\CategorySales;
use App\Models\Invoicemnt;
use App\Models\Processing;
use App\Traits\Pagination;
use App\Traits\SendResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoicmentController extends Controller
{
    use SendResponse, Pagination;
    public function getInvoicemnt()
    {
        $get = Invoicemnt::select("*");
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10000;
        $res = $this->paging($get,  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب الفواتير بنجاح', [], $res["model"], null, $res["count"]);
    }
    public function addInvoicemnt(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            // to make invoice
            'invoice_no' => 'required|unique:invoicemnts,invoice_no',
            'sequence' => 'required',
            // to save process
            "sale_category_id" => "required|exists:category_sales,id",
            "driver_name" => "required",
            "car_number" => "required",
            "quantity_car" => "required",

        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'حصل خطأ في ادخال البيانات', $validator->errors(), []);
        }
        $sale_category = CategorySales::find($request["sale_category_id"]);
        if ($sale_category->status == 0) {
            return $this->send_response(400, 'هذه الموقع قيد الانتضار حالياً', [], []);
        }

        $data = [];
        $data = [
            "sale_category_id" => $request["sale_category_id"],
            "employee_id" => auth()->user()->id,
            "driver_name" => $request["driver_name"],
            "car_number" => $request["car_number"],
            "quantity_car" => $request["quantity_car"],
            'invoice_no' => $request['invoice_no'],
            'sequence' => $request['sequence'],
        ];
        $sale_category->update(['actual_quantity' => $sale_category->actual_quantity + $request['quantity_car']]);
        $add_invoice = Invoicemnt::create($data);

        $sale_category->update(['status' => 2]);  // to make sale category process done 
        return $this->send_response(200, 'تم اضافة فاتورة بنجاح', [], Invoicemnt::find($add_invoice->id));
    }
}
