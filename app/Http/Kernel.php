<?php

namespace App\Http;

use App\Http\Middlewares\VerifyCsrfToken;
use App\Http\Middlewares\Authenticate;
use App\Http\Middlewares\RedirectIfAuthenticated;
use Setup\Core\HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        VerifyCsrfToken::class,
    ];

    protected $middlewareAliases = [
        'auth' => Authenticate::class,
        'guest' => RedirectIfAuthenticated::class
    ];
}