<?php

/**
 * Created by PhpStorm.
 * User: Shahnawaz
 * Date: 6/9/2016
 * Time: 2:07 PM
 */
class Test extends CI_Controller
{
    public function index()
    {
        echo $this->input->get('title');
    }

    public function hello($var="def")
    {
        echo "This is hello function." . $var;
        echo $this->input->get('title');
    }
}

?>