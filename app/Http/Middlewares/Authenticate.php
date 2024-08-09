<?php

namespace App\Http\Middlewares;

use Setup\Transport\Request;

class Authenticate extends ChainChecker
{
    public function handle(Request $request)
    {
        if(! $request->user()) abort(401);

        $this->next($request);
    }
}