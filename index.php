<?php

require __DIR__.'/Core/Router.php';
use Core\Router;

class root
{
    public static function init()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $_SERVER["CONTENT_TYPE"] =  'application/json';
        header('Content-Type: application/json; charset=utf-8');
        return Router::init();
    }
}
$data = root::init();
print_r(json_encode($data, JSON_UNESCAPED_UNICODE));