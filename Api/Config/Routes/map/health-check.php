<?php

$router->namespace('\\Api\\Controller\\Standard');
$router->group('/');
$router->get('/health-check', 'HealthCheck:check', 'open');
