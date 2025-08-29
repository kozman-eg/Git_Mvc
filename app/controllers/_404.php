<?php

namespace Controllers;

defined('ROOTPATH') or exit('access allwoed');


class _404
{
    use \Core\Controller;

    public function index($a = '', $b = '', $c = '')
    {
        echo "this is 404  controller " . $a . $b . $c;
    }
}
