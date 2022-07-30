<?php

namespace App\Http\Controllers;

use App\Models\CategorySales;
use App\Traits\Pagination;
use App\Traits\SendResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class salesCategoryController extends Controller
{
    use SendResponse, Pagination;

    public function addSale(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'place' => 'required',
            'name_customer' => 'required',
            'type' => 'required',
            'quantity' => 'required',
            'man_buliding' => 'required',
            'workers' => 'required',
            'bump' => 'required',
            'time' => 'required',
            'date' => 'required',
            'name_representative' => 'required',
            'phone_number' => 'required',
            'price' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'حصل خطأ في ادخال البيانات', $validator->errors(), []);
        }
        $data = [];
        $data = [
            "employee_id" => auth()->user()->id,
            'place' => $request['place'],
            'name_customer' => $request['name_customer'],
            'type' => $request['type'],
            'quantity' => $request['quantity'],
            'man_buliding' => $request['man_buliding'],
            'workers' => $request['workers'],
            'bump' => $request['bump'],
            'time' => $request['time'],
            'date' => $request['date'],
            'name_representative' => $request['name_representative'],
            'phone_number' => $request['phone_number'],
            'price' => $request['price'],
            'notes' => $request['notes'] ?? null,
        ];

        $sales = CategorySales::create($data);
        return $this->send_response(200, 'تم اضافة مبيعات بنجاح', [], CategorySales::find($sales->id));
    }

    public function getSale()
    {
        $get = CategorySales::select("*");
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($get->orderBy("status", "ASC"),  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب المبيعات بنجاح', [], $res["model"], null, $res["count"]);
    }
    public function getDoneInvoice()
    {
        $get = CategorySales::where("status", 3);
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($get,  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب المبيعات بنجاح', [], $res["model"], null, $res["count"]);
    }

    public function sendingToProcessing(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            "sale_category_id" => 'required|exists:category_sales,id'
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'فشل عملية تسجيل الدخول', $validator->errors(), []);
        }

        $auth = auth()->user();
        if ($auth->user_type == 1 || $auth->user_type == 0) {
            $sale_category = CategorySales::find($request["sale_category_id"]);
            $sale_category->update(['status' => 1]);
            return $this->send_response(200, 'تم الترحيل الى المعمل', [], CategorySales::find($request["sale_category_id"]));
        } else {
            return $this->send_response(400, 'لاتمتلك الصلاحية للترحيل الى المعمل', [], []);
        }
    }
    // to get data after add and convert to process
    public function getSalesProcessing()
    {
        $get = CategorySales::where("status", 1);
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($get,  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب المبيعات قيد الانتضار بنجاح', [], $res["model"], null, $res["count"]);
    }
    public function getSalesDone()
    {
        $get = CategorySales::where("status", 2);
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($get,  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب المبيعات قيد التنفيذ بنجاح', [], $res["model"], null, $res["count"]);
    }

    public function doneInvoice(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            "sale_category_id" => 'required|exists:category_sales,id'
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'فشل عملية تسجيل الدخول', $validator->errors(), []);
        }

        $auth = auth()->user();
        if ($auth->user_type == 1 || $auth->user_type == 0) {
            $sale_category = CategorySales::find($request["sale_category_id"]);
            $sale_category->update(['status' => 3]);
            return $this->send_response(200, 'تم الانتهاء من العمل', [], CategorySales::find($request["sale_category_id"]));
        } else {
            return $this->send_response(400, 'لاتمتلك الصلاحية للترحيل الى المعمل', [], []);
        }
    }
}