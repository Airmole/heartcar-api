<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use App\Services\WechatService;
use Illuminate\Http\Request;

class WechatController extends Controller
{

    public function getOpenid(Request $request)
    {
        $jscode = $request->input("jscode");
        $wechatService = new WechatService();
        $this->responseJson(200, $wechatService->getOpenid($jscode));
    }

    public function login(Request $request)
    {
        $type = $request->input('type');
        $data = $request->except('type');
        $result = [];
        if ($type == 'user') {
            $result = User::where('openid', $data['openid'])->first();
        }
        if ($type == 'driver') {
            $result = Driver::where('openid', $data['openid'])->first();
        }
        if (empty($result)) {
            $this->responseJson(403, [ 'message' => '未注册' ]);
        }
        if ($result) {
            $result['type'] = $type;
            $this->responseJson(200, [ 'message' => 'success', 'data' => $result ]);
        }
    }

}
