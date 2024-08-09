<?php

namespace Setup\Routing;

use Setup\Core\Application;
use Setup\Exceptions\RouteException;
use Setup\Routing\Resource;

class Router
{
    public function __construct(protected Application $app){}
    
    protected $routes = [];

    protected function add(string $uri, string $method, array $resource)
    {
        $this->routes[] = [
            "uri" => $this->build_uri_patten($uri),
            "method" => $method,
            "resource" => new Resource($resource[0], $resource[1])
        ];
        // dd(env('DB_CONNECTION'));
        return $this;
    }

    public function get(string $uri, array $resource)
    {
        return $this->add($uri, 'GET', $resource);
    }

    public function post(string $uri, array $resource)
    {
        return $this->add($uri, 'POST', $resource);
    }

    public function put(string $uri, array $resource)
    {
        return $this->add($uri, 'PUT', $resource);
    }

    public function patch(string $uri, array $resource)
    {
        return $this->add($uri, 'PATCH', $resource);
    }

    public function delete(string $uri, array $resource)
    {
        return $this->add($uri, 'DELETE', $resource);
    }

    public function route($uri, $method)
    {

        foreach($this->routes as $route){

            $params = $this->find($route, $uri, $method);

            if($params === false) continue;

            return $route['resource']->grantAccess($this->app->request)->execute($params, $this->app);
        }
        // abort();
        throw RouteException::notFound($uri);
    }

    public function find($route, string $uri, string $method)
    {
        if(preg_match($route['uri'], rtrim($uri, '/'), $matches) && $route['method'] === $method)
        {
            return array_intersect_key($matches, array_flip(array_filter(array_keys($matches), 'is_string')));
        }
        return false;
    }

    public function middleware(array|string $alias)
    {
        $aliases = is_string($alias) ? [$alias] : $alias;
        $this->getLastKthResource()->protect($aliases);
    }

    public function getLastKthResource(int $n = 1) : Resource
    {
        return $this->routes[count($this->routes) - $n]['resource'];
    }


    public function build_uri_patten($uri)
    {
        $pattern = preg_replace('/\//', '\\/', $uri);
        $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^\/]+)', $pattern);
        $pattern = '/^' . $pattern . '$/i';

        return $pattern;
    }
}