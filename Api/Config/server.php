<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000'); // Ou especifique o domínio se for necessário
header('Access-Control-Allow-Methods: POST, PUT, GET, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type, Authorization, HTTP_X_CSRF_TOKEN'); // Adicione o X-CSRF-TOKEN

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Se for uma requisição OPTIONS, apenas retorna 200 OK
    header("HTTP/1.1 200 OK");
    exit;
}

header('Charset: utf-8');
date_default_timezone_set('America/Sao_Paulo');
