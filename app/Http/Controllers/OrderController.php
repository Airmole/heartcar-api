<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Price;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = (int)$request->input("page", 1);
        $offset = ($currentPage - 1) * $perPage;
        $type = $request->input("type", 'user') == 'driver' ? [Order::TYPE_SHARE, Order::TYPE_SELF] : [Order::TYPE_SHARE];
        $data = Order::whereIn('type', $type)
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
        $isDriver = $request->input("driver", 0);
        $column = $isDriver ? 'driver_id' : 'user_id';
        if ($isDriver) $user = Driver::where('mobile', $request->input('mobile'))->first();
        $shareOrderIds = Destination::where('user_id', $user->id)->pluck('id');
        $data = Order::whereIn('status', $status)
            ->where(function ($query) use ($column, $user, $shareOrderIds) {
                $query->where($column, $user->id)->orWhere(function ($query) use ($shareOrderIds) {
                    $query->whereIn('id', $shareOrderIds);
                });
            })
            ->offset($offset)->limit($perPage)->orderBy('id', 'desc')->get();
        $dataSum = Order::whereIn('status', $status)
            ->where(function ($query) use ($column, $user, $shareOrderIds) {
                $query->where($column, $user->id)->orWhere(function ($query) use ($shareOrderIds) {
                    $query->whereIn('id', $shareOrderIds);
                });
            })
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

    public function accept(Request $request ,$id)
    {
        $data = $request->all();
        $driver = Driver::where('mobile', $data['mobile'])->first();
        if (empty($driver)) {
            $this->responseJson(403, ['desc' => '请先注册司机']);
        }

        $order = Order::where('id', $id)->update([
            'driver_id' => $driver->id,
            'start_time' => date("Y-m-d H:i:s"),
            'status' => Order::STATUS_ACCEPT
        ]);
        $result = [ 'code' => 200, 'data' => $order ];
        $this->responseJson(200, $result);
    }

    public function finish(Request $request ,$id)
    {
        $data = $request->all();
        $isDriver = $request->input("driver", 0);
        if ($isDriver) {
            $driver = Driver::where('mobile', $data['mobile'])->first();
            if (empty($driver)) {
                $this->responseJson(403, ['desc' => '请先注册司机']);
            }

            $order = Order::find($id);
            if ($order->driver_id != $driver->id) {
                $this->responseJson(403, ['desc' => '非本单司机，无法完成订单']);
            }
        } else {
            $user = User::where('mobile', $data['mobile'])->first();
            if (empty($user)) {
                $this->responseJson(403, ['desc' => '非注册用户']);
            }

            $order = Order::find($id);
            if ($order->user_id != $user->id) {
                $this->responseJson(403, ['desc' => '非本单乘客，无法完成订单']);
            }
        }

        $order = Order::where('id', $id)->update([
            'stop_time' => date("Y-m-d H:i:s"),
            'status' => Order::STATUS_FINISHED
        ]);
        $result = [ 'code' => 200, 'data' => $order ];
        $this->responseJson(200, $result);
    }

    public function join(Request $request ,$id)
    {
        $data = $request->all();
        $user = User::where('mobile', $data['mobile'])->first();
        if (empty($user)) {
            $this->responseJson(403, ['desc' => '请先注册']);
        }

        $data['user_id'] = $user->id;
        $data['order_id'] = $id;

        $destination = Destination::create($data);
        if ($destination) {
            $order = Order::find($id);
            $order->destinations = array_merge($order->destinations ?: [], [$destination->id]);
            $order->passengers = array_merge($order->passengers ?: [], [$user->id]);
            $order->satatus = Order::STATUS_PICKING;
            $order->save();
            $result = [ 'code' => 200, 'data' => $destination ];
            $this->responseJson(200, $result);
        }
    }

    // 计算车费价格
    public function fee(Request $request)
    {
        $distance = $request->input('distance', 0);
        $type = $request->input('type', 'inpooling_price');
        $total = $this->calcFee($distance, $type);
        $result = [ 'code' => 200, 'data' => $total ];
        $this->responseJson(200, $result);
    }

    public function calcFee($distance, $type)
    {
        $price = Price::where('distance_start', '<', $distance)->where('distance_end', '>=', $distance)->first();
        if (empty($price)) {
            $price = Price::orderBy('distance_end', 'desc')->first();
        }
        $typePrice = $type == 'self' ? 'inpooling_price' : 'pooling_price';
        $total = sprintf("%.2f", $distance * ($price->$typePrice));
        return $total;
    }


}
