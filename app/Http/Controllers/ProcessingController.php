<?php

namespace App\Http\Controllers;

use App\Models\Processing;
use App\Traits\Pagination;
use App\Traits\SendResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProcessingController extends Controller
{
    use SendResponse, Pagination;
    public function addToDone(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            "sale_category_id" => "required|exists:category_sales,id",
            "driver_name" => "required",
            "car_number" => "required",
            "quantity_car" => "required",

        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'حصل خطأ في ادخال البيانات', $validator->errors(), []);
        }

        $data = [];
        $data = [
            "sale_category_id" => $request["sale_category_id"],
            "employee_id" => auth()->user()->id,
            "driver_name" => $request["driver_name"],
            "car_number" => $request["car_number"],
            "quantity_car" => $request["quantity_car"],
        ];

        $processing = Processing::create($data);
        return $this->send_response(200, 'تم اكمال العملية بنجاح', [], Processing::find($processing->id));
    }
}
