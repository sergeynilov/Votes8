<?php
// app/Services/GoogleLogin.php

namespace App\Services;
use App\Http\Traits\funcsTrait;

class GoogleLogin
{
    protected $client;
    use funcsTrait;

    public function __construct(\Google_Client $client)
    {
        $this->client = $client;


        $this->debToFile(print_r(\Config::get('google.client_id'),true),'  GoogleLogin  -1 \Config::get(\'google.client_id\')::');

        $this->client->setClientId(\Config::get('google.client_id'));
        $this->client->setClientSecret(\Config::get('google.client_secret'));
        $this->client->setDeveloperKey(\Config::get('google.api_key'));
        $this->client->setRedirectUri(\Config::get('app.url') . "/loginCallback");
        $this->client->setScopes([
            'https://www.googleapis.com/auth/youtube',
        ]);
        $this->client->setAccessType('offline');
    }

    public function isLoggedIn()
    {
        $this->debToFile(print_r(-11,true),'  GoogleLogin  -11::');
        if (\Session::has('token')) {
            $this->debToFile(print_r(\Session::get('token'),true),'  GoogleLogin  -2 \Session::get(\'token\')::');
            $this->client->setAccessToken(\Session::get('token'));
        }

        if ($this->client->isAccessTokenExpired()) {
//            $refresh_token_value= $this->client->refreshToken();
            $refresh_token_value= $this->client->getRefreshToken();
            $this->debToFile(print_r( $refresh_token_value,true),'  GoogleLogin  -3 $refresh_token_value()::');
//            \Session::put('token', $this->client->getRefreshToken());

            session(['token' => $refresh_token_value]);
//            \Session::set('token', $this->client->getRefreshToken());
        }
        $this->debToFile(print_r(-12,true),'  GoogleLogin  -12::');

        return ! $this->client->isAccessTokenExpired();
    }

    public function login($code)
    {
        $this->debToFile(print_r( $code,true),'  GoogleLogin  -4 $code::');
        $this->client->authenticate($code);
        $token = $this->client->getAccessToken();
        \Session::put('token', $token);

        return $token;
    }

    public function getLoginUrl()
    {
        $authUrl = $this->client->createAuthUrl();
        $this->debToFile(print_r( $authUrl,true),'  GoogleLogin  -5 $authUrl::');

        return $authUrl;
    }
}