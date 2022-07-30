<?php

namespace App\Http\Controllers;

use App\Traits\SendResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use SendResponse;
    public function login(Request $request)
    {
        $request = $request->json()->all();
        $validator = Validator::make($request, [
            'password' => 'required',
            "full_name" => 'required',
        ], [
            'full_name.required' => ' يرجى ادخال اسم المستخدم ',
            'password.required' => 'يرجى ادخال كلمة المرور ',
        ]);
        if ($validator->fails()) {
            return $this->send_response(400, 'فشل عملية تسجيل الدخول', $validator->errors(), []);
        }
        if (auth()->attempt(array('full_name' => $request['full_name'], 'password' => $request['password']))) {
            $user = auth()->user();
            $token = $user->createToken('shop_managment')->accessToken;
            return $this->send_response(200, 'تم تسجيل الدخول بنجاح', [], $user, $token);
        } else {
            return $this->send_response(400, 'هناك مشكلة تحقق من تطابق المدخلات', null, null, null);
        }
    }
}