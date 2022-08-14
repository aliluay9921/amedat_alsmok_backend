<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Pagination;
use App\Traits\SendResponse;
use Illuminate\Http\Request;
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
        return $this->send_response(200, 'تم اضافة مندوب بنجاح', [], User::find($user->id));
    }
}