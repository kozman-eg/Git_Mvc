<?php

namespace Controllers;

defined('ROOTPATH') or exit('access allwoed');

class User
{
    use \Core\Controller;


    public function index($a = '', $b = '', $c = '')
    {
        echo "this is User controller" . $a . $b . $c;
    }
}
