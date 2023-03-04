<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function index()
    {
        return view('Auth/V_Login');
    }

    public function checkLogin()
    {
        $username = $this->request->getVar('username');
        $password = md5($this->request->getVar('password'));

        $check_login = $this->modelUser->check_login($username, $password);

        if ($check_login) {
            session()->set('log', true);
            session()->set('name', $check_login['namaUser']);
            session()->set('akses', $check_login['akses']);

            return redirect()->to('/home');
        } else {
            session()->setFlashdata('error', 'Username atau Password Anda Salah!');
            return redirect()->back();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
