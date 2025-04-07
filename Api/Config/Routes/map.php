<?php

use Api\Lib\Current\Routes;

$router = Routes::getInstance()->router();
$router->group('/');
$files = [
    'health-check',
    'transfer',
    'register'
];
foreach ($files as $file) {
    require_once DIR_APP . 'Api/Config/Routes/map/' . $file . '.php';
}