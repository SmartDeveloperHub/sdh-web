<?php
/**
 * Created by PhpStorm.
 * User: xafilox
 * Date: 9/09/15
 * Time: 11:26
 */

namespace SdhWeb\Extensions;

use GuzzleHttp\Client as GuzzleClient;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\RequestException;


class SdhApi
{
    private static function request($method, $url, array $config, $useAuthToken = true)
    {

        $client = new GuzzleClient();

        $config['http_errors'] = false;

        if(Session::has('SdhApiToken')) {
            if(!isset($config['headers']))  $config['headers'] = [];
            $config['headers']['Authorization'] = 'Bearer '. Session::get('SdhApiToken');
        }

        try {

            $sdhApiUrl = ends_with($_ENV['SDH_API_INTERNAL'], '/') ? substr($_ENV['SDH_API_INTERNAL'], 0, -1) : $_ENV['SDH_API_INTERNAL'];
            return $client->request($method, $sdhApiUrl . $url, $config);

        } catch (RequestException $e) {
            Log::error('Unable to connect to SDH API.');
            Debugbar::addException($e);

            return null;
        }

    }


    public static function login($username, $password) {

        $res = SdhApi::request('POST', '/auth/login/', [
            'form_params' => [
                'username' => $username,
                'password' => $password
            ]
        ]);

        if($res !== null && $res->getStatusCode() == 200) {

            $response = $array = json_decode($res->getBody()->getContents(), true);

            return $response;

        }

        return null;
    }

    /**
     * @return bool True if user is logged out.
     */
    public static function logout() {

        if(!Session::has('User')) {
            return true;
        }

        $res = SdhApi::request('POST', '/auth/logout/', []);

        if($res !== null && $res->getStatusCode() == 200) {

            //Remove from session
            Session::forget('SdhApiToken');
            Session::forget('User');

            return true;

        }

        return false;
    }

}