<?php

namespace App\Controllers;

use \App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('login');
    }
    public function register()
    {
        return view('register');
    }

    public function logout()
    {
        $session = session();
        $ses_data = [
            'id' => '',
            'name' => '',
            'username' => '',
            'role' => '',
            'isLoggedIn' => FALSE
        ];
        $session->set($ses_data);
        return redirect()->to(base_url() . '/login');
    }


    public function loginPost()
    {


        $session = session();

        $userModel = new UserModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $data = $userModel->where('username', $username)->first();

        if ($data) {
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if ($authenticatePassword) {
                $ses_data = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'username' => $data['username'],
                    'role' => $data['role'],
                    'isLoggedIn' => TRUE
                ];

                $session->set($ses_data);
                return redirect()->to(base_url() . '/');
            } else {
                $session->setFlashdata('msg', 'Password is incorrect.');
                return redirect()->to(base_url() . '/login');
            }
        } else {
            $session->setFlashdata('msg', 'Username does not exist.');
            return redirect()->to(base_url() . '/login');
        }
    }

    public function registerPost()
    {
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'username'         => 'required|min_length[4]',
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];

        if ($this->validate($rules)) {
            $userModel = new UserModel();

            $data = [
                'name'     => $this->request->getVar('name'),
                'username' => $this->request->getVar('username'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];

            $userModel->save($data);

            return redirect()->to(base_url() . '/login');
        } else {
            $data['validation'] = $this->validator;
            echo view('register', $data);
        }
    }
}
