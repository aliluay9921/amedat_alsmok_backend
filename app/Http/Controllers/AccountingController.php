<?php

namespace App\Http\Controllers;

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
}