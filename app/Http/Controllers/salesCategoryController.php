<?php

namespace App\Http\Controllers;

use App\Traits\Pagination;
use App\Traits\SendResponse;
use Illuminate\Http\Request;
use App\Models\CategorySales;
use Illuminate\Support\Facades\Schema;
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

        ], [
            'place.required' => 'يرجى ادخال الحقل ',
            'name_customer.required' => 'يرجى ادخال الحقل ',
            'type.required' => 'يرجى ادخال الحقل ',
            'quantity.required' => 'يرجى ادخال الحقل ',
            'man_buliding.required' => 'يرجى ادخال الحقل ',
            'workers.required' => 'يرجى ادخال الحقل ',
            'bump.required' => 'يرجى ادخال الحقل ',
            'time.required' => 'يرجى ادخال الحقل ',
            'date.required' => 'يرجى ادخال الحقل ',
            'phone_number.required' => 'يرجى ادخال الحقل ',
            'name_representative.required' => 'يرجى ادخال الحقل ',
            'price.required' => 'يرجى ادخال الحقل ',
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
            'degree' => $request['degree'],

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
        $saels = CategorySales::select("*");
        // if (isset($_GET["proces_type"])) {
        //     $saels = CategorySales::whereIn("proces_type", $_GET['proces_type']);
        //     return $saels->get();
        // }
        if (isset($_GET['query'])) {
            $saels->where(function ($q) {
                $columns = Schema::getColumnListing('category_sales');
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $_GET['query'] . '%');
                }
            });
        }
        if (isset($_GET['filter'])) {
            $filter = json_decode($_GET['filter']);
            $saels->where($filter->name, $filter->value);
        }
        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key == 'skip' || $key == 'limit' || $key == 'query' || $key = 'filter') {
                    continue;
                } else {
                    $sort = $value == 'true' ? 'desc' : 'asc';
                    $saels->orderBy($key,  $sort);
                }
            }
        }
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($saels->orderBy("status", "ASC"),  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب المبيعات بنجاح', [], $res["model"], null, $res["count"]);
    }



    public function sendingToProcessing(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            "sale_category_id" => 'required|exists:category_sales,id',
            'proces_type' => 'required'
        ], [
            'proces_type.required' => 'لايمكنك الترحيل دون اختيار المعمل'
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'خطأ في المدخلات', $validator->errors(), []);
        }

        $auth = auth()->user();
        if ($auth->user_type == 1 || $auth->user_type == 0) {
            $sale_category = CategorySales::find($request["sale_category_id"]);
            $sale_category->update([
                'status' => 1,
                'proces_type' => $request['proces_type'],
            ]);
            return $this->send_response(200, 'تم الترحيل الى المعمل', [], CategorySales::find($request["sale_category_id"]));
        } else {
            return $this->send_response(400, 'لاتمتلك الصلاحية للترحيل الى المعمل', [], []);
        }
    }
    // to get data after add and convert to process



    public function doneInvoice(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            "sale_category_id" => 'required|exists:category_sales,id'
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'خطأ في المدخلات', $validator->errors(), []);
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
    public function updateSaleCategory(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'sale_category_id' => 'required|exists:category_sales,id',
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
        ], [
            'sale_category_id.required' => 'يجب ادخال  العنصر المراد التعديل عليه',
            'sale_category_id.exists' => 'العنصر الذي قمت بأدخاله غير موجود',
            'place.required' => 'يرجى ادخال الحقل ',
            'name_customer.required' => 'يرجى ادخال الحقل ',
            'type.required' => 'يرجى ادخال الحقل ',
            'quantity.required' => 'يرجى ادخال الحقل ',
            'man_buliding.required' => 'يرجى ادخال الحقل ',
            'workers.required' => 'يرجى ادخال الحقل ',
            'bump.required' => 'يرجى ادخال الحقل ',
            'time.required' => 'يرجى ادخال الحقل ',
            'date.required' => 'يرجى ادخال الحقل ',
            'phone_number.required' => 'يرجى ادخال الحقل ',
            'name_representative.required' => 'يرجى ادخال الحقل ',
            'price.required' => 'يرجى ادخال الحقل ',
        ]);
        if ($validator->fails()) {
            return $this->send_response(401, 'خطأ بالمدخلات', $validator->errors(), []);
        }
        $data = [];
        $data = [
            "employee_id" => auth()->user()->id,
            'place' => $request['place'],
            'name_customer' => $request['name_customer'],
            'type' => $request['type'],
            'degree' => $request['degree'],
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
        $sale_category = CategorySales::find($request['sale_category_id']);
        if ($sale_category->status != 0) {
            return $this->send_response(401, 'لايمكنك التعديل على عنصر تم ترحيله مسبقاً الى المعمل', [], []);
        }
        $sale_category->update($data);
        return $this->send_response(200, 'تم التعديل على العنصر بنجاح', [], CategorySales::find($sale_category->id));
    }
    public function deleteSaleCategory(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'sale_category_id' => 'required|exists:category_sales,id'
        ], [
            'sale_category_id.required' => 'يجب ادخال  العنصر المراد حذفه',
            'sale_category_id.exists' => 'العنصر الذي قمت بأدخاله غير موجود',
        ]);
        if ($validator->fails()) {
            return $this->send_response(401, 'خطأ بالمدخلات', $validator->errors(), []);
        }
        $sale_category = CategorySales::find($request['sale_category_id']);
        if ($sale_category->status != 0) {
            return $this->send_response(400, 'لايمكنك حذف عنصر تم ترحيله الى المعمل سابقاً', [], []);
        }
        $sale_category->delete();
        return $this->send_response(200, 'تم حذف العنصر', [], []);
    }
}