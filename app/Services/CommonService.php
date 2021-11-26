<?php


namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;

class CommonService
{

    public function httpPost(string $referer, string $url, string $post, string $cookie, $timeout = false)
    {
        $timeout = $timeout === false ? (int)getenv("EDU_TIMEOUT") : $timeout;
        preg_match('/^http:\/\/(.*?)\/|^https:\/\/(.*?)\//', $url, $domain);
        $host = empty($domain[1]) ? $domain[2] : $domain[1];
        $origin = substr($domain[0], 0, -1);

        $headers = [
            "Host: {$host}",
            'Connection: keep-alive',
            'Content-Length: ' . strlen($post),
            'Cache-Control: max-age=0',
            'Upgrade-Insecure-Requests: 1',
            'Origin: ' . $origin,
            'Content-Type: application/x-www-form-urlencoded',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: zh'
        ];

        if (!empty($referer)){
            $headers[] = "Referer: {$referer}";
        }

        if(!empty($cookie)){
            $headers[] = "Cookie: {$cookie}";
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['code' => $httpCode, 'data' => $response];
    }

    public function httpGet(string $referer, string $url, string $cookie, bool $headerHost = true)
    {
        if ($headerHost) {
            preg_match('/^http:\/\/(.*?)\/|^https:\/\/(.*?)\//', $url, $host);
            $host = $host[1] ?? '';
        }
        $headers = [
            'Connection: keep-alive',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: zh',
        ];
        if (!empty($host)){
            $headers[] = "Host: {$host}";
        }
        if (!empty($referer)){
            $headers[] = "Referer: {$referer}";
        }
        if(!empty($cookie)){
            $headers[] = "Cookie: {$cookie}";
        }
        $timeout = getenv("EDU_TIMEOUT");
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers
        ));
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['code' => $httpCode, 'data' => $response];
    }


    protected function responseJson(int $code, array $body)
    {
        throw new HttpResponseException(response()->json($body)->setStatusCode($code));
    }
}
