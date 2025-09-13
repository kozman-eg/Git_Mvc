<?php

namespace Controllers;


defined('ROOTPATH') or exit('access allwoed');


class Home
{
    use \Core\Controller;
    use \Models\Pager;


    public $userkey = 'Name';


    public function index($a = '', $b = '', $c = '')
    {
        $ses = new \Models\Session;
        if (!$ses->is_logged_in()) {
            return redirect('login');
        }
        $date['Name'] = empty($_SESSION['User']['Name']) ? 'User' : $_SESSION['User']['Name'];
       
        $this->view('home', $date);
    }
    public function edit($a = '', $b = '', $c = '',)
    {
        show("this is edit home controller");
        show($a);
        show($b);
        show($c);
        $this->view('home');
    }
}
