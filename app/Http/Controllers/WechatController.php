<?php

namespace App\Http\Controllers;

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

}
