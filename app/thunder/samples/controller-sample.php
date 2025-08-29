<?php

namespace Controllers;

defined('ROOTPATH') or exit('access not allowed');

class {CLASSNAME}
{
    use \Models\Pager;

    public function index($a = '', $b = '', $c = '')
    {
        $ses = new \Models\Session;

        if (!$ses->is_logged_in()) {
            return redirect('login');
        }

        $data = [];
        $this->view('{classname}', $data);
    }

    public function edit($a = '', $b = '', $c = '')
    {
        $data = [];
        $this->view('{classname}', $data);
    }
}
