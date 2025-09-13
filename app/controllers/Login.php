<?php

namespace Controllers;


class Login
{
    use \Core\Controller;



    public function index($a = '', $b = '', $c = '')
    {
        $data['user'] =  new \Models\User;

        $req = new \Models\Request;
        $ses = new \Models\Session;
        
        if ($ses->is_logged_in()) {
            return redirect('home');
        }
        if ($req->posted()) {

            $data['user']->login($_POST);
            $data['errors'] = $data['user']->errors ?? [];
        } else {
            $data['errors'] =  [];
        }

        $this->view('login', $data);
    }
}
