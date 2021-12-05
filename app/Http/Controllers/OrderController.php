<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = (int)$request->input("page", 1);
        $offset = ($currentPage - 1) * $perPage;
        $data = Order::where('type', Order::TYPE_SHARE)
            ->whereIn('status', [Order::STATUS_WAIT, Order::STATUS_ACCEPT])
            ->offset($offset)->limit($perPage)->orderBy('id', 'desc')->get();
        $dataSum = Order::where('type', Order::TYPE_SHARE)
            ->whereIn('status', [Order::STATUS_WAIT, Order::STATUS_ACCEPT])
            ->count();
        if ($data) {
            $pagination = [
                'current' => $currentPage,
                'last'    => (int)ceil($dataSum / $perPage) > 1 ? (int)ceil($dataSum / $perPage) : 1
            ];
            $result = [ 'code' => 200, 'data' => $data, 'pagination' => $pagination ];
            $this->responseJson(200, $result);
        }
    }

    public function my(Request $request)
    {
        $user = User::where('mobile', $request->input('mobile'))->first();
        if (empty($user)) {
            $this->responseJson(403, ['desc' => '请先注册']);
        }

        $type = $request->input('type');
        $status = [
            Order::STATUS_WAIT,
            Order::STATUS_ACCEPT,
            Order::STATUS_PICKING,
            Order::STATUS_FINISHED,
            Order::STATUS_CANCELED
        ];
        if ($type == 'picking') {
            $status = [
                Order::STATUS_WAIT,
                Order::STATUS_ACCEPT,
                Order::STATUS_PICKING
            ];
        }
        if ($type == 'finish') {
            $status = [
                Order::STATUS_FINISHED,
                Order::STATUS_CANCELED
            ];
        }

        $perPage = 10;
        $currentPage = (int)$request->input("page", 1);
        $offset = ($currentPage - 1) * $perPage;
        $data = Order::where('user_id', $user->id)
            ->whereIn('status', $status)
            ->offset($offset)->limit($perPage)->orderBy('id', 'desc')->get();
        $dataSum = Order::where('user_id', $user->id)
            ->whereIn('status', $status)
            ->count();
        if ($data) {
            $pagination = [
                'current' => $currentPage,
                'last'    => (int)ceil($dataSum / $perPage) > 1 ? (int)ceil($dataSum / $perPage) : 1
            ];
            $result = [ 'code' => 200, 'data' => $data, 'pagination' => $pagination ];
            $this->responseJson(200, $result);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = User::where('mobile', $data['mobile'])->first();
        if (empty($user)) {
            $this->responseJson(403, ['desc' => '请先注册']);
        }

        $data['order_no'] = Order::createNewOrderNo();
        $data['user_id'] = $user->id;

        $order = Order::create($data);
        if ($order) {
            $result = [ 'code' => 200, 'data' => $order ];
            $this->responseJson(200, $result);
        }
    }

    public function show($id)
    {
        $order = Order::with(['user', 'driver'])->find($id);
        $result = [ 'code' => 200, 'data' => $order ];
        $this->responseJson(200, $result);
    }

    public function cancel($id)
    {
        $order = Order::where('id', $id)->update([ 'status' => Order::STATUS_CANCELED ]);
        $result = [ 'code' => 200, 'data' => $order ];
        $this->responseJson(200, $result);
    }

}
