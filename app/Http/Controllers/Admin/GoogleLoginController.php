<?php
// app/Http/Controllers/GoogleLoginController.php

class GoogleLoginController extends Controller
{

    public function index(\App\Services\GoogleLogin $ga)
    {
        if ($ga->isLoggedIn()) {
            return \Redirect::to('/');
        }

        $loginUrl = $ga->getLoginUrl();

        return "<a href='{$loginUrl}'>login</a>";
    }

    public function store(\App\Services\GoogleLogin $ga)
    {
// User rejected the request
        if (\Input::has('error')) {
            dd(\Input::get('error'));
        }

        if (\Input::has('code')) {
            $code = \Input::get('code');
            $ga->login($code);

            return \Redirect::to('/');
        } else {
            throw new \InvalidArgumentException("Code attribute is missing.");
        }//else
    }
}