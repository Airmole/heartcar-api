<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function register(Request $request)
    {
        $type = $request->input('type');
        $data = $request->except('type');
        if ($type == 'user') {
            User::create($data);
        }
        if ($type == 'driver') {
            Driver::create($data);
        }
        $this->responseJson(200, [ 'message' => 'success' ]);
    }

    public function login(Request $request)
    {
        $type = $request->input('type');
        $data = $request->except('type');
        $result = [];
        if ($type == 'user') {
            $result = User::where('mobile', $data['mobile'])->first();
        }
        if ($type == 'driver') {
            $result = Driver::where('mobile', $data['mobile'])->first();
        }
        if (empty($result)) {
            $this->responseJson(403, [ 'message' => '未注册' ]);
        }
        if ($result && $result->password == $data['password']) {
            $result['type'] = $type;
            $this->responseJson(200, [ 'message' => 'success', 'data' => $result ]);
        }
        $this->responseJson(403, [ 'message' => '密码错误' ]);
    }

}
