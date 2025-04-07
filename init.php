<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pathToConfigs = __DIR__ . '/Api/Config/';

$fileToIncludes = [
  'server',
  'constants',
  'environment' 
];

foreach ($fileToIncludes as $file) {
  require_once $pathToConfigs . $file . '.php';
}
