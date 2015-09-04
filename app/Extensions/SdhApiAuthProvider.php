<?php

namespace SdhWeb\Extensions;


use Barryvdh\Debugbar\Facade as Debugbar;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
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
        $res = Session::get('LdapUser');

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
            return new GenericUser((array) Session::get('LdapUser'));
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

        $client = new GuzzleClient();
        try {

            $res = $client->post('http://10.0.2.1:8080/auth/login/', [
                'form_params' => [
                    'username' => $username,
                    'password' => $password
                ],
                'http_errors' => false
            ]);

            if($res->getStatusCode() == 200) {

                $response = $array = json_decode($res->getBody()->getContents(), true);

                if($response != null) {
                    Debugbar::info($response);
                    //Define the id to use
                    $response['user']['id'] = (isset($response['user']['id']) ?  $response['user']['id'] : $response['user']['uidNumber']);
                    $response['user']['name'] = (isset($response['user']['name']) ?  $response['user']['name'] : $response['user']['cn']);


                    Session::put('SdhApiToken', $response['token']);
                    Session::put('LdapUser', $response['user']);

                    return true;
                } else {
                    Log::error('Invalid JSON received from SDH API.');
                    Debugbar::warning('Invalid JSON received from SDH API.');
                }

            }

        } catch(RequestException $e) {
            Log::error('Unable to connect to SDH API.');
            Debugbar::addException($e);
        }

        return false;

    }

}