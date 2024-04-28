<?php

namespace App\Http\Controllers;

use App\Traits\Pagination;
use App\Traits\SendResponse;
use Illuminate\Http\Request;
use App\Models\CategorySales;
use App\Models\Invoicemnt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;

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
            'phone_number' => 'required',
            'price' => 'required',
            'representative_id' => auth()->user()->user_type == 0 ? 'required' : ''

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
            'price.required' => 'يرجى ادخال الحقل ',
            'representative_id.required' => 'يجب اختيار المندوب '
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'حصل خطأ في ادخال البيانات', $validator->errors(), []);
        }
        $user = auth()->user()->id;
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
            'representative_id' => $request['representative_id'] ?? $user, // if user type == 0 add request['representive_id'] else if user_type == 4 add  $user
            'phone_number' => $request['phone_number'],
            'price' => $request['price'],
            'notes' => $request['notes'] ?? null,
            'Type_pouring' => $request['Type_pouring'] ?? null,
            'workers_group' => $request['workers_group'] ?? null,
            'status' => auth()->user()->user_type == 6 ? 3 : 0,
            'proces_type' => auth()->user()->user_type == 6 ? 3 : 0,
            'final_quantity' => auth()->user()->user_type == 6 ? $request['final_quantity'] : 0,
            'actual_quantity' => auth()->user()->user_type == 6 ? $request['actual_quantity'] : 0,
            'created_at' => auth()->user()->user_type == 6 ? $request['date'] : now()
        ];
        // $check_bump =  CategorySales::where("bump", $data['bump'])->where('date', $data['date'])->where('time', $data['time'])->first();
        // $check_bump =  CategorySales::where([
        //     "bump" => $data['bump'],
        //     'date' => $data['date'],
        //     'time' => $data['time']
        // ])->first();
        // return $data['time'];
        // $expload = explode("-", $request['time']);
        // $time_plus = $expload[1] - 1 . '-' . $expload[0];
        // return $time_plus;



        $check_bump =  CategorySales::where(function ($q) use ($request) {
            $q->where("bump", $request['bump'])->where('date', $request['date'])->where('time', $request['time']);
        })->get();

        // ->where('time', $expload[1] + 1 . '-' . $expload[0])
        // return $check_bump;
        if ($check_bump->count() > 0) {
            return $this->send_response(400, 'لايوجد وقت كافي لأستخدام هذا البمب', [], []);
        }
        $sales = CategorySales::create($data);
        return $this->send_response(200, 'تم اضافة مبيعات بنجاح', [], CategorySales::find($sales->id));
    }

    public function getSale()
    {
        if (auth()->user()->user_type == 4) {
            error_log(auth()->user()->id);
            $saels = CategorySales::where('representative_id', auth()->user()->id);
        } else {
            $saels = CategorySales::select("*");
            if (isset($_GET["proces_type"])) {
                $saels = CategorySales::whereIn("proces_type", json_decode($_GET['proces_type'][0]));
                // return $saels->get();
            }
        }

        if (isset($_GET['query'])) {
            $saels->where(function ($q) {
                $q->whereHas('representativ', function ($q) {
                    $q->Where('full_name', 'LIKE', '%' . $_GET['query'] . '%');
                });
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
        if (isset($_GET['filter_date'])) {
            if ($_GET["filter_date"] != "" || $_GET["filter_date"] != null) {
                $saels->where('date', $_GET['filter_date']);
            }
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
            $_GET['limit'] = 50;
        $res = $this->paging($saels->orderBy("status", "ASC"),  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب المبيعات بنجاح', [], $res["model"], null, $res["count"]);
    }

    public function getSalesToRepresentatives()
    {
        $saels = CategorySales::select("*")->where('representative_id', "!=", auth()->user()->id);
        if (isset($_GET["proces_type"])) {
            $saels = CategorySales::whereIn("proces_type", json_decode($_GET['proces_type'][0]));
            // return $saels->get();
        }
        // 6ba2cc16-33a0-413a-a146-5f49cbbd1415
        if (isset($_GET['query'])) {
            $saels->where(function ($q) {
                $q->whereHas('representativ', function ($q) {
                    $q->Where('full_name', 'LIKE', '%' . $_GET['query'] . '%');
                });
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
        if (isset($_GET['filter_date'])) {
            if ($_GET["filter_date"] != "" || $_GET["filter_date"] != null) {
                $saels->where('date', $_GET['filter_date']);
            }
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
            $_GET['limit'] = 50;
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
        if ($auth->user_type == 1 || $auth->user_type == 0 || $auth->user_type == 6) {
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
            "sale_category_id" => 'required|exists:category_sales,id',
            "final_quantity" => "required"
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'خطأ في المدخلات', $validator->errors(), []);
        }

        $auth = auth()->user();
        if ($auth->user_type == 1 || $auth->user_type == 0 || $auth->user_type == 2) {
            $sale_category = CategorySales::find($request["sale_category_id"]);
            $sale_category->update([
                'status' => 3,
                'final_quantity' => $request["final_quantity"]
            ]); //معناها الصبة تم  تنفيذها
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
            'representative_id' => 'required',
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
            'representative_id.required' => 'يرجى ادخال الحقل ',
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
            'representative_id' => $request['representative_id'],
            'phone_number' => $request['phone_number'],
            'price' => $request['price'],
            'notes' => $request['notes'] ?? null,
            'Type_pouring' => $request['Type_pouring'] ?? null,
            'workers_group' => $request['workers_group'] ?? null,
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

    public function goBump(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'sale_category_id' => 'required|exists:category_sales,id'
        ], [
            'sale_category_id.required' => 'يجب ادخال  العنصر المراد ترحيل البم اليه',
            'sale_category_id.exists' => 'العنصر الذي قمت بأدخاله غير موجود',
        ]);
        if ($validator->fails()) {
            return $this->send_response(401, 'خطأ بالمدخلات', $validator->errors(), []);
        }

        $sale_category = CategorySales::find($request['sale_category_id']);
        $sale_category->update([
            'go_bump' => true
        ]);
        return $this->send_response(200, 'تم ترحيل البم بنجاح', [], CategorySales::find($request['sale_category_id']));
    }

    public function addNote(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'sale_category_id' => 'required|exists:category_sales,id',
            'notes' => 'required'
        ], [
            'sale_category_id.required' => 'يجب ادخال  العنصر المراد أضافة ملاحضة اليه',
            'sale_category_id.exists' => 'العنصر الذي قمت بأدخاله غير موجود',
            'note.required' => 'يجب أدخال الملاحضة'
        ]);
        if ($validator->fails()) {
            return $this->send_response(401, 'خطأ بالمدخلات', $validator->errors(), []);
        }
        $sale_category = CategorySales::find($request['sale_category_id']);
        $sale_category->update([
            'notes' => $sale_category->notes . "  " . $request['notes']
        ]);
        return $this->send_response(200, 'تم أضافة ملاحضة بنجاح', [], CategorySales::find($request['sale_category_id']));
    }

    public function deleteManagerSaleCategory(Request $request)
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
        // $sale_category = CategorySales::find($request['sale_category_id']);
        // $sale_category->delete();
        $sale_category = CategorySales::find($request['sale_category_id']);
        Invoicemnt::where("sale_category_id", $sale_category->id)->delete();
        $sale_category->delete();
        return $this->send_response(200, 'تم حذف العنصر', [], []);
    }
}
