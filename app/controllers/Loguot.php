<?php

namespace Controllers;


class Loguot
{
    use \Core\Controller;

    public function index($a = '', $b = '', $c = '')
    {
        $ses = new \Models\Session;
        $ses->loguot();
        redirect('home');
    }
}
