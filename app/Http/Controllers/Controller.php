<?php

namespace App\Http\Controllers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected function responseJson(int $code, array $body)
    {
        throw new HttpResponseException(response()->json($body)->setStatusCode($code));
    }
}
