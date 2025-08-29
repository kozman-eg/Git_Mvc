<?php

namespace Controllers;


class Product 
{
    use \Core\Controller;


    public function index($a ='', $b = '', $c = '')
    {
        echo "this is Product controller    " .$a .$b .$c ;
        //echo "  this is home controller  "; //.$a .$b .$c ;
        $this->view('products/product');
    }

}
