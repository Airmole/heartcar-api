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

}
