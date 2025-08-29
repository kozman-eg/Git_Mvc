<?php

namespace Controllers;


defined('ROOTPATH') or exit('access allwoed');


class Home
{
    use \Core\Controller;
    use \Models\Pager;


    public $userkey = 'UserName';


    public function index($a = '', $b = '', $c = '')
    {
        $ses = new \Models\Session;
        if (!$ses->is_logged_in()) {
            return redirect('login');
        }
        $date['UserName'] = empty($_SESSION['User']['UserName']) ? 'User' : $_SESSION['User']['UserName'];
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
