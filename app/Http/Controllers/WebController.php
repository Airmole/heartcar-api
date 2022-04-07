<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Price;
use App\Models\User;
use Illuminate\Http\Request;

class WebController extends Controller
{

    public function workplace()
    {
        $orderCount = Order::count();
        $userCount = User::count();
        $driverCount = Driver::count();
        $result = [
            'order' => $orderCount,
            'user' => $userCount,
            'driver' => $driverCount
        ];
        $this->responseJson(200, $result);
    }

    public function user(Request $request)
    {
        $pageSize = (int)$request->input("pagesize", 10);
        $data = User::orderBy('id', 'desc')->paginate($pageSize);

        $result = [
            'data' => $data->items(),
            'totalCount' => $data->total(),
            'totalPage' => $data->lastPage(),
            'pageNo' => $data->currentPage(),
            'pageSize' => $data->perPage()
        ];

        $this->responseJson(200, $result);
    }

    public function userStatus(Request $request, $id)
    {
        $status = $request->input('status');
        $user = User::find($id);
        $user->status = $status ? 1 : 0;
        $user->save();
        $this->responseJson(200, ['data' => $user]);
    }

    public function removeUser(Request $request, $id)
    {
        User::where('id', $id)->delete();
        $this->responseJson(200, ['data' => '删除成功']);
    }

    public function driver(Request $request)
    {
        $pageSize = (int)$request->input("pagesize", 10);
        $data = Driver::orderBy('id', 'desc')->paginate($pageSize);

        $result = [
            'data' => $data->items(),
            'totalCount' => $data->total(),
            'totalPage' => $data->lastPage(),
            'pageNo' => $data->currentPage(),
            'pageSize' => $data->perPage()
        ];

        $this->responseJson(200, $result);
    }

    public function driverStatus(Request $request, $id)
    {
        $status = $request->input('status');
        $user = Driver::find($id);
        $user->status = $status ? 1 : 0;
        $user->save();
        $this->responseJson(200, ['data' => $user]);
    }

    public function removeDriver(Request $request, $id)
    {
        Driver::where('id', $id)->delete();
        $this->responseJson(200, ['data' => '删除成功']);
    }

    public function allPrice()
    {
        $data = Price::all()->toArray();
        $this->responseJson(200, $data);
    }

    public function changePrice(Request $request)
    {
        Price::truncate();
        Price::insert($request->all());
        $this->responseJson(200, ['data' => '删除成功']);
    }

    public function order(Request $request)
    {
        $pageSize = (int)$request->input("pagesize", 10);
        $data = Order::with(['user', 'driver'])->orderBy('id', 'desc')->paginate($pageSize);

        $result = [
            'data' => $data->items(),
            'totalCount' => $data->total(),
            'totalPage' => $data->lastPage(),
            'pageNo' => $data->currentPage(),
            'pageSize' => $data->perPage()
        ];

        $this->responseJson(200, $result);
    }
}
