<?php

use Setup\Core\ConfigDirParser;
use Setup\Transport\Request;
use Setup\Transport\Response;
use Setup\Volatile\Cookie;
use Setup\Volatile\Session;

function dd($data)
{
    echo '<pre>';
    die(var_dump($data));
    echo '</pre>';
}

function base_path($path = null)
{
    return __DIR__ . '/../' . $path;
}

function app_path($path = null)
{
	return base_path("app/{$path}");
}

function resource_path($path = null)
{
    return base_path("resources/{$path}");
}

function storage_path($path = null)
{
    return base_path("public/storage/{$path}");
}

function request()
{
    return new Request;
}

function response()
{
    return new Response;
}

function view($path, $attributes = [])
{
    $path = str_replace('.', '/', $path);
    response()->view($path, $attributes);
}

function session()
{
    return new Session;
}

function cookie()
{
    return new Cookie;
}

function env($key, $default = false)
{
    return $_ENV[$key] ?? $default;
}

function abort($code = 404, $message = "Not Found")
{
    response()->abort($code, $message);
}

function navigateTo($path)
{
    response()->redirect($path);
}

function config(string $accessors)
{
    return ConfigDirParser::parse($accessors)->get();
}

