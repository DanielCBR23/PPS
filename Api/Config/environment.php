<?php

use Api\Lib\Standard\Environment\Variables;

$environment = Variables::getInstance()->get('ENVIRONMENT');

$environmentFiles = [
    'localhost' => 'Environment/Localhost.php',
    'development' => 'Environment/Development.php',
    'production' => 'Environment/Production.php',
];

if (isset($environmentFiles[$environment])) {
    require_once $environmentFiles[$environment];
}