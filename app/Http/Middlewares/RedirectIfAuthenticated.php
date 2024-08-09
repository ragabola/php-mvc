<?php

namespace App\Http\Middlewares;

use Setup\Transport\Request;

class RedirectIfAuthenticated extends ChainChecker
{
    public function handle(Request $request)
    {
        if($request->user()) navigateTo('/home');

        $this->next($request);   
    }
}