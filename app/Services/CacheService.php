<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class CacheService
{
    public function setWechatAccessTokenCache(string $accessToken, int $ttl=7200)
    {
        $keyname = "weapp_access_token";
        Redis::set($keyname, $accessToken);
        Redis::expire($keyname, $ttl);
    }

    public function getWechatAccessTokenCache()
    {
        $keyname = "weapp_access_token";
        $cache = Redis::get($keyname);
        if($cache){
            return $cache;
        }
        return false;
    }

}
