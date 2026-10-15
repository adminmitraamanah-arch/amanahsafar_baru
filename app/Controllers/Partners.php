<?php

namespace App\Controllers;

class Partners extends BaseController
{
    public function login()
    {
        return redirect()->to('/login');
    }

    public function register()
    {
        return redirect()->to('/register');
    }

    public function verification()
    {
        return redirect()->to('/verify');
    }

    public function agreement()
    {
        return redirect()->to('/agreement');
    }

    public function test()
    {
        return redirect()->to('/login');
    }
}
