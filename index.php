<?php

require __DIR__.'/Core/Router.php';
use Core\Router;

class root
{
    public static function init()
    {
        return Router::init();
    }
}
$data = root::init();
var_dump($data);