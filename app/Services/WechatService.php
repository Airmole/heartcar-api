<?php

namespace App\Services;

class WechatService
{
    public $weappAppid;
    public $weappSecret;
    public $wechatApiDomain = 'https://api.weixin.qq.com';

    public function __construct()
    {
        $this->weappAppid = getenv("WEAPP_APPID");
        $this->weappSecret = getenv("WEAPP_SECRET");
    }

    public function getOpenid(string $jscode)
    {
        $domain = $this->wechatApiDomain;
        $appid = $this->weappAppid;
        $secret = $this->weappSecret;
        $commonService = new CommonService();
        $url = "{$domain}/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$jscode}&grant_type=authorization_code";
        $openidRequest = $commonService->httpGet('', $url, '');

        return json_decode($openidRequest['data'], true);
    }

    public function getAccessToken()
    {
        $cacheService = new CacheService();
        $cache = $cacheService->getWechatAccessTokenCache();
        if($cache){
            return $cache;
        }

        $domain = $this->wechatApiDomain;
        $appid = $this->weappAppid;
        $secret = $this->weappSecret;
        $url = "{$domain}/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
        $commonService = new CommonService();
        $accessTokenRequest = $commonService->httpGet('', $url, '');
        $requestData = json_decode($accessTokenRequest['data'], true);
        if(isset($requestData['access_token']) && !empty($requestData['access_token'])){
            $accessToken = $requestData['access_token'];
            $ttl = $requestData['expires_in'];
            $cacheService->setWechatAccessTokenCache($accessToken, $ttl);
            return $accessToken;
        }

        return $accessTokenRequest;
    }


    public function weapiPost(string $url, string $post)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => env("EDU_TIMEOUT", 5),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     * ?????????????????????????????????????????????????????????.
     * @param $sessionKey string ??????????????????
     * @param $encryptedData string ?????????????????????
     * @param $iv string ??????????????????????????????????????????
     * @param $data string ??????????????????
     *
     * @return int ??????0?????????????????????????????????
     */
    public function decryptData($sessionKey, $encryptedData, $iv, &$data)
    {
        if (strlen($sessionKey) != 24) {
            return -41001;
        }
        $aesKey = base64_decode($sessionKey);

        if (strlen($iv) != 24) {
            return -41002;
        }

        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj = json_decode($result);
        if ($dataObj == NULL) {
            return -41003;
        }
        if ($dataObj->watermark->appid != $this->weappAppid) {
            return -41003;
        }

        $data = $result;
        return 0;
    }
}
