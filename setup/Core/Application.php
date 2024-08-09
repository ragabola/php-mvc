<?php

namespace Setup\Core;

use Setup\Routing\Router;
use Setup\Transport\Request;
use Setup\Transport\Response;
use Setup\Volatile\Session;

class Application
{

    protected static $instance;
    protected Container $container;
    protected string $basePath;
    protected string $routesPath;
    protected Router $router;

    private function __construct(){}

    public static function configure(string $basePath = null)
    {
        if (!self::$instance) self::$instance = new self;

        self::$instance->basePath = $basePath;
        self::$instance->container = new Container;
        return self::$instance;
    }

    public function withRoutes($path)
    { 
        $this->routesPath = $path;
        return $this;
    }

    public function create()
    {
        $this->loadEnv();
        $this->binder();
        $this->startSession();
        $this->loadHelpers();
        $this->loadRoutes();

        return $this;
    }

    public function binder()
    {
        $this->request = fn() => new Request;
        $this->respond = fn() => new Response;
        $this->session = fn() => new Session;
    }

    public function startSession()
    {
        $this->session->start();
    }

    public function loadRoutes()
    {
        $router = new Router($this);
        require_once $this->routesPath;
        $this->router = $router;
    }

    protected function loadHelpers()
    {
        require_once $this->basePath . '/setup/helpers.php';
    }

    public function loadEnv()
    {
        DotEnv::load($this->basePath . '/.env');
    }

    public function handleRequest(Request $request)
    {
        $this->router->route($request->path(), $request->method());
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->container, $name)) {
            return $this->container->{$name}(...$arguments);
        }
    }

    public function __set($name, $value)
    {
        return $this->container->bind($name, $value);
    }

    public function __get($name)
    {
        return $this->container->resolve($name);
    }
}