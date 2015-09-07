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

            $sdhApiUrl = ends_with($_ENV['SDH_API_INTERNAL'], '/') ? substr($_ENV['SDH_API_INTERNAL'], 0, -1) : $_ENV['SDH_API_INTERNAL'];
            $res = $client->post($sdhApiUrl.'/auth/login/', [
                'form_params' => [
                    'username' => $username,
                    'password' => $password
                ],
                'http_errors' => false
            ]);

            if($res->getStatusCode() == 200) {

                $response = $array = json_decode($res->getBody()->getContents(), true);

                if($response != null) {

                    $user = array();

                    //Matches the ldap property with the local user property
                    $matchups = array(
                        'uidNumber' => 'id',
                        'uid' => 'username',
                        'givenName' => 'name',
                        'sn' => 'surname',
                    );

                    Debugbar::info($response);

                    //Fill the user array with the corresponding property of the ldap user
                    foreach($matchups as $ldapProp => $localProp) {
                        if(isset($response[$ldapProp])) {
                            $user[$localProp] = $response[$ldapProp];
                        }
                    }

                    //Store in session
                    Session::put('SdhApiToken', $response['token']);
                    Session::put('User', $user);

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