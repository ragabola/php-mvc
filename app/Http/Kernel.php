<?php

namespace App\Http;

use App\Http\Middlewares\VerifyCsrfToken;
use App\Http\Middlewares\Authenticate;
use App\Http\Middlewares\ChainChecker;
use App\Http\Middlewares\RedirectIfAuthenticated;
use Setup\Exceptions\MiddlewareException;
use Setup\Transport\Request;

class Kernel
{
    protected $middlewareGroups = [
        VerifyCsrfToken::class,
    ];

    protected $middlewareAliases = [
        'auth' => Authenticate::class,
        'guest' => RedirectIfAuthenticated::class
    ];

    protected $routeMiddlewares = [];


    public function load(array $aliases)
    {
        if(count($aliases))
        {
            foreach($aliases as $alias) $this->validateAlias($alias);
        }
    
        return $this;
    }

    public function check(Request $request)
    {
        $this->getRouteMiddlewares()?->handle($request);
    }

    public function getRouteMiddlewares() : ?ChainChecker
    {
        $middlewares = array_merge($this->middlewareGroups, $this->routeMiddlewares);

        $front = null;
        $current = null;

        for($i = 0; $i <= count($middlewares) - 1; $i++)
        {
            $current ??= new $middlewares[$i];
            $next = isset($middlewares[$i + 1]) ? new $middlewares[$i + 1] : null;

            $current->setSuccessor($next);
            if(! $front) $front = $current;

            $current = $next;
        }

        return $front;
    }

    protected function validateAlias(string $alias)
    {
        if(! isset($this->middlewareAliases[$alias])) throw MiddlewareException::notFound($alias);
        array_push($this->routeMiddlewares, $this->middlewareAliases[$alias]);
    }
}