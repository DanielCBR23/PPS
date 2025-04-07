<?php

namespace Api\Lib\Standard;

use Api\Exceptions\FactoryException;
use CoffeeCode\Router\Dispatch;
use CoffeeCode\Router\Router as CoffeCodeRouter;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Router extends CoffeCodeRouter
{

    private $configs = [];

    private const VALID_CONFIG_ROUTES = [
        'open',
        'auth'
    ];

    public function getRoute(): array
    {
        if (empty($this->route)) {
            $this->setRouteToUse();
        }
        return is_array($this->route) ? $this->route : [];
    }

    private function setRouteToUse(): void 
    {
        $this->patch = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (empty($this->routes) || empty($this->routes[$this->httpMethod])) {
            $this->error = self::NOT_IMPLEMENTED;
            return;
        }
        $this->route = null;
        foreach ($this->routes[$this->httpMethod] as $key => $route) {
            
            if (preg_match("#^$key$#", $this->patch)) {
                $this->route = $route;
                break;
            }
        }
    }
    

    public function group(?string $group): Dispatch
    {
        if ($group && substr($group, 0, 1) == '/') {
            $group = substr($group, 1);
        }

        $this->group = ($group ? $group : null);
        return $this;
    }

    public function get(string $route, $handler, ?string $name = null): void
    {
        $this->appendConfig($handler, $name);
        parent::get($route, $handler);
    }

    public function post(string $route, $handler, ?string $name = null): void {
        $this->appendConfig($handler, $name);
        parent::post($route, $handler);
    }
    

    public function put(string $route, $handler, ?string $name = null): void
    {
        $this->appendConfig($handler, $name);
        parent::put($route, $handler);
    }

    public function delete(string $route, $handler, ?string $name = null): void
    {
        $this->appendConfig($handler, $name);
        parent::delete($route, $handler);
    }

    private function appendConfig($handler, ?string $config = ''): void
    {   
        if (is_null($config) || empty($config)) {
            $exception = 'Standard\EndpointKey\InvalidConfigRouteException';
            throw FactoryException::create($exception, [$handler]);
        }

        $this->checkConfig($config);

        $object = implode('\\', array_filter([$this->namespace, $handler]));
        $this->configs[$object] = [
            'handler' => $handler,
            'config' => $config
        ];
    }

    private function checkConfig(string $config): void
    {
        if (!in_array($config, self::VALID_CONFIG_ROUTES)) {
            $exception = 'Standard\EndpointKey\InvalidConfigEndpointException';
            throw FactoryException::create($exception, [$config]);
        }
    }

    private function getRouteConfig(): array
    {
        $route = $this->route;
        $key = $route['handler'] . ':' . $route['action'];
        if (isset($this->configs[$key])) {
            return $this->configs[$key];
        }
        return [];
    }

    public function isOpenRoute(): bool
    {
        $configs = $this->getRouteConfig();
        return $configs['config'] == 'open';
    }

    public function skipUser(): bool 
    {
        $configs = $this->getRouteConfig();
        return $configs['config'] == 'skipUser';
    }
}