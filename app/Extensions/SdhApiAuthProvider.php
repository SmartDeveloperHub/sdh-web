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

use Barryvdh\Debugbar\Facade as Debugbar;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SdhApiAuthProvider implements UserProvider
{

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $id
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveById($identifier)
    {
        $res = Session::get('User');

        return new GenericUser((array) $res);
    }

    /**
     * Retrieve a user by by their unique identifier and "remember me" token.
     *
     * @param  mixed   $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        return new \Exception('not implemented');
    }


    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        new \Exception('not implemented');
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $username = $credentials['username'];
        $password = $credentials['password'];

        if($this->authenticateWithApi($username, $password)) {
            return new GenericUser((array) Session::get('User'));
        }

    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        //We assume that if he user was retrieved it is good
        return true;
    }

    private function authenticateWithApi($username, $password)
    {

        $response = SdhApi::login($username, $password);

        if($response != null) {

            $user = array();

            //Matches the ldap property with the local user property
            $toRename = array(
                'uidNumber' => 'id',
                'uid' => 'username',
                'givenName' => 'name',
                'sn' => 'surname',
            );

            Debugbar::info($response);

            //Fill the user array with the ldap user renamin the required properties
            foreach($response['user'] as $ldapProp => $value) {
                if(isset($toRename[$ldapProp])) {
                    $localProp = $toRename[$ldapProp];
                    $user[$localProp] = $value;
                } else {
                    $user[$ldapProp] = $value;
                }
            }
            /*foreach($toRename as $ldapProp => $localProp) {
                if(isset($response['user'][$ldapProp])) {
                    $user[$localProp] = $response['user'][$ldapProp];
                }
            }*/

            //Store in session
            Session::put('SdhApiToken', $response['token']);
            Session::put('User', $user);

            return true;

        } else {
            Log::error('Invalid JSON received from SDH API.');
            Debugbar::warning('Invalid JSON received from SDH API.');
        }

        return false;

    }

}