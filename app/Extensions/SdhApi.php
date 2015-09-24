<?php namespace SdhWeb\Extensions;
/*
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      This file is part of the Smart Developer Hub Project:
        http://www.smartdeveloperhub.org/
      Center for Open Middleware
            http://www.centeropenmiddleware.com/
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      Copyright (C) 2015 Center for Open Middleware.
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
      Licensed under the Apache License, Version 2.0 (the "License");
      you may not use this file except in compliance with the License.
      You may obtain a copy of the License at
                http://www.apache.org/licenses/LICENSE-2.0
      Unless required by applicable law or agreed to in writing, software
      distributed under the License is distributed on an "AS IS" BASIS,
      WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
      See the License for the specific language governing permissions and
      limitations under the License.
    #-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=#
*/

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