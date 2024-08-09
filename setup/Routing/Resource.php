<?php

namespace Setup\Routing;

use App\Http\Kernel;
use ReflectionMethod;
use Setup\Core\Application;
use Setup\Transport\Request;

class Resource
{
    protected array $middlewares = [];
    public function __construct(protected $controller, protected string $method){}

    public function protect(array $middlewares)
    {
        $this->middlewares = $middlewares;
    }
    
    public function grantAccess(Request $request)
    {
        (new Kernel)->load($this->middlewares)->check($request);
        return $this;
    }

    public function execute(array $params, Application $app)
    {
        $method = new ReflectionMethod($this->controller, $this->method);
        $pass = [];

        foreach($method->getParameters() as $param)
        {
            if($app->has($param->name)) array_push($pass, $app->resolve($param->name));
        }

        $pass = [...$pass, ...array_values($params)];
        
        $method->invokeArgs(new $this->controller, $pass);
        
    }
}