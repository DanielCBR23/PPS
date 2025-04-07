<?php
$router->namespace('\\Api\\Controller');
$router->post('/transfer', 'Transfer:index', 'open');