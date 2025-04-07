<?php

namespace Api\Lib\Current;

use Api\Lib\Standard\Router;

class Routes
{

    private static $instance,
        $router;

    private $path = 'Api/Config/Routes/';

    public static function getInstance(): self
    {
        if (self::$instance == null) {
            self::$instance = new Routes();
        }
        return self::$instance;
    }

    public function __construct()
    {
        self::$router = new Router(URL_SYSTEM);
    }

    public function router(): Router
    {
        return self::$router;
    }

    public function init(): void
    {
        $this->appendRoutes();
        $this->appendOfflineRoutes();
    }

    private function appendRoutes(): void
    {
        require $this->path . 'map.php';
    }
    
    private function appendOfflineRoutes(): void
    {
        if (IS_OFFLINE) {
            require $this->path . 'offline.php';
        }
    }
    
}