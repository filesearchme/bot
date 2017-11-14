<?php
namespace App\Hosts;

class GetData {

    public static function parse($url, $timeout, $server_addr, $curl=false)
    {
        if( $curl == false )
        {
            $client = new \GuzzleHttp\Client([
                'http_errors' => false,
                'headers' => [
                    'User-Agent' => 'FileSearch.me Bot v'.env('APP_BOT_VERSION').' '.md5($server_addr)
                ]
            ]);
            $res = $client->request('GET', $url);
            return $res->getStatusCode() == 200 ? $res->getBody() : '';
        }
        else
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT,'FileSearch.me Bot v'.env('APP_BOT_VERSION').' '.md5($server_addr));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
    }
}