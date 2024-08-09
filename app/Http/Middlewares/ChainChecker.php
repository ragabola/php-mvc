<?php

namespace App\Http\Middlewares;

use Setup\Transport\Request;

abstract class ChainChecker
{
    protected ?ChainChecker $successor = null;

    public function setSuccessor(?ChainChecker $successor)
    {
        $this->successor = $successor;
    }

    public abstract function handle(Request $request);
    public function next(Request $request)
    {
        if($this->successor) $this->successor->handle($request);
    }
}