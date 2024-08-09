<?php

namespace App\Http\Middlewares;

use Setup\Core\ChainChecker;
use Setup\Transport\Request;

class VerifyCsrfToken extends ChainChecker
{
    public function handle(Request $request)
    {
        // middleware logic here

        $this->next($request);
    }
}