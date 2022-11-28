<?php

namespace Crater\Space;

use Crater\Models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Implementation taken from Akaunting - https://github.com/akaunting/akaunting
trait SiteApi
{
    protected static function getRemote($url, $data = [])
    {
        //$base = 'https://craterapp.com/';
        //$base = 'https://qbh-server-dev-dot-qbh-soft.uc.r.appspot.com/api/v1/';
        $base = 'https://fd.findforme.in/api/v1/';



        $client = new Client(['verify' => false, 'base_uri' => $base]);

        $headers['headers'] = [
            'Accept' => 'application/json',
            'Referer' => url('/'),
            'crater' => Setting::getSetting('version'),
        ];

        $data['http_errors'] = false;

        $data = array_merge($data, $headers);
       // dd($url);
        try {
            $result = $client->get($url, $data);
         //  dd($result);
        } catch (RequestException $e) {
            $result = $e;
        }

        return $result;
    }
}
