<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Driver;
use App\Models\User;
use App\Models\UserInfo;
use App\Traits\Pagination;
use App\Traits\SendResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AccountingController extends Controller
{
    use SendResponse, Pagination;

    public function getRepresentatives()
    {
        $users = User::where('user_type', 4);
        if (isset($_GET['query'])) {
            $users->where(function ($q) {
                $columns = Schema::getColumnListing('users');
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $_GET['query'] . '%');
                }
            });
        }
        if (isset($_GET['filter'])) {
            $filter = json_decode($_GET['filter']);
            $users->where($filter->name, $filter->value);
        }
        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key == 'skip' || $key == 'limit' || $key == 'query' || $key = 'filter') {
                    continue;
                } else {
                    $sort = $value == 'true' ? 'desc' : 'asc';
                    $users->orderBy($key,  $sort);
                }
            }
        }
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($users->orderBy("created_at", "desc"),  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب المندوبين بنجاح', [], $res["model"], null, $res["count"]);
    }

    public function getDrivers()
    {
        $drivers = Driver::select("*");
        if (isset($_GET['query'])) {
            $drivers->where(function ($q) {
                $columns = Schema::getColumnListing('drivers');
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $_GET['query'] . '%');
                }
            });
        }

        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key == 'skip' || $key == 'limit' || $key == 'query') {
                    continue;
                } else {
                    $sort = $value == 'true' ? 'desc' : 'asc';
                    $drivers->orderBy($key,  $sort);
                }
            }
        }
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($drivers->orderBy("created_at", "desc"),  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب السواق بنجاح', [], $res["model"], null, $res["count"]);
    }
    public function getCars()
    {
        $cars = Car::select("*");
        if (isset($_GET['query'])) {
            $cars->where(function ($q) {
                $columns = Schema::getColumnListing('cars');
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $_GET['query'] . '%');
                }
            });
        }

        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                if ($key == 'skip' || $key == 'limit' || $key == 'query') {
                    continue;
                } else {
                    $sort = $value == 'true' ? 'desc' : 'asc';
                    $cars->orderBy($key,  $sort);
                }
            }
        }
        if (!isset($_GET['skip']))
            $_GET['skip'] = 0;
        if (!isset($_GET['limit']))
            $_GET['limit'] = 10;
        $res = $this->paging($cars->orderBy("created_at", "desc"),  $_GET['skip'],  $_GET['limit']);
        return $this->send_response(200, 'تم جلب السيارات بنجاح', [], $res["model"], null, $res["count"]);
    }

    public function addRepresentative(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'full_name' => 'required',
            "user_name" => "required|unique:users,user_name",
            "password" => "required",
        ], [
            'full_name.required' => 'يجب ادخال اسم المندوب',
            'user_name.required' => 'يجب ادخال اسم مستخدم للمندوب',
            'user_name.unique' => 'اسم المستخدم الذي قمت بأدخاله مستخدم سابقاً',
            'password.required' => 'يجب أدخال كلمة مرور',
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'حصل خطأ في ادخال البيانات', $validator->errors(), []);
        }
        $data = [];
        $data = [
            "full_name" => $request["full_name"],
            "user_name" => $request["user_name"],
            "password" => bcrypt($request["password"]),
            'user_type' => 4

        ];
        $user = User::create($data);
        UserInfo::create([
            "user_id" => $user->id,
            "password" => $request["password"]
        ]);
        return $this->send_response(200, 'تم اضافة مندوب بنجاح', [], User::find($user->id));
    }

    public function updateRepresentive(Request $request)
    {
        $request = $request->json()->all();
        $representive = User::find($request['id']);
        $validator = Validator::make($request, [
            'id' => 'required|exists:users,id',
            'full_name' => 'required',
            "user_name" => "required|unique:users,user_name," . $representive->id,
        ], [
            'full_name.required' => 'يجب ادخال اسم المندوب',
            'user_name.required' => 'يجب ادخال اسم مستخدم للمندوب',
            'user_name.unique' => 'اسم المستخدم الذي قمت بأدخاله مستخدم سابقاً',
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'حصل خطأ في ادخال البيانات', $validator->errors(), []);
        }
        $data = [];
        $data = [
            "full_name" => $request["full_name"],
            "user_name" => $request["user_name"],
        ];
        $representive->update($data);
        return $this->send_response(200, 'تم تعديل معلومات المندوب بنجاح', [], User::find($representive->id));
    }

    public function deleteRepresentive(Request $request)
    {

        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'representive_id' => 'required|exists:users,id'
        ], [
            'representive.required' => 'يجب ادخال  العنصر المراد حذفه',
            'representive.exists' => 'العنصر الذي قمت بأدخاله غير موجود',
        ]);
        if ($validator->fails()) {
            return $this->send_response(401, 'خطأ بالمدخلات', $validator->errors(), []);
        }
        $representive = User::find($request['representive_id']);
        if ($representive->user_type != 4) {
            return $this->send_response(400, 'لايمكنك حذف هذا العنصر', [], []);
        }
        $representive->delete();
        return $this->send_response(200, 'تم حذف المندوب بنجاح', [], []);
    }

    public function showPassword(Request $request)
    {
        $request = $request->json()->all();
        $get = UserInfo::where('user_id', $request['user_id'])->get();
        return $this->send_response(200, 'عرض كلمة المرور بنجاح', [], $get);
    }

    public function addDriver(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'full_name' => 'required',
        ], [
            'full_name.required' => 'يجب ادخال اسم سائق',
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'حصل خطأ في ادخال البيانات', $validator->errors(), []);
        }
        $data = [];
        $data = [
            "full_name" => $request["full_name"],
        ];
        $driver = Driver::create($data);
        return $this->send_response(200, 'تم اضافة سائق  بنجاح', [], Driver::find($driver->id));
    }

    public function addCar(Request $request)
    {

        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'car_number' => 'required|unique:cars,car_number',
            'car_sequence' => 'required|unique:cars,car_sequence',
        ], [
            'car_number.required' => 'يجب أدخال رقم السيارة',
            'car_number.unique' => 'رقم السيارة الذي قمت بأدخاله مخزن سابقاً',
            'car_sequence.required' => 'يجب أدخال تسلسل السيارة',
            'car_sequence.unique' => 'تسلسل السيارة الذي قمت بأدخاله مخزن سابقاً',
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'حصل خطأ في ادخال البيانات', $validator->errors(), []);
        }
        $data = [];
        $data = [
            "car_number" => $request["car_number"],
            "car_sequence" => $request["car_sequence"],
        ];
        $car = Car::create($data);
        return $this->send_response(200, 'تم اضافة سيارة  بنجاح', [], Car::find($car->id));
    }

    public function deleteDriver(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'driver_id' => 'required|exists:drivers,id'
        ], [
            'driver_id.required' => 'يجب ادخال  العنصر المراد حذفه',
            'driver_id.exists' => 'العنصر الذي قمت بأدخاله غير موجود',
        ]);
        if ($validator->fails()) {
            return $this->send_response(401, 'خطأ بالمدخلات', $validator->errors(), []);
        }
        $driver = Driver::find($request['driver_id']);
        $driver->delete();
        return $this->send_response(200, 'تم حذف السائق بنجاح', [], []);
    }
}