<?php

namespace Setup\Transport;

class Request 
{
    public static function capture()
    {
        return new static;
    }
    
    public function url()
    {
        return $this->isSecure() ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function path()
    {
        return parse_url($this->url(), PHP_URL_PATH);
    }

    public function method()
    {
        return $_POST["_method"] ?? $_SERVER['REQUEST_METHOD'];
    }

    public function isMethod($method)
    {
        return $this->method() === strtoupper($method);
    }

    public function isSecure()
    {
        return ($_SERVER['HTTPS'] ?? '') === 'on';
    }

    public function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function referer()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    public function expectsJson()
    {
        return str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');
    }

    public function expectsHtml()
    {
        return $_SERVER['HTTP_ACCEPT'] === 'text/html';
    }

    public function all() 
    {
        return $_REQUEST;
    }

    public function has($key)
    {
        return $_REQUEST[$key] ?? false;
    }

    public function get($key, $default = null)
    {
        return $_REQUEST[$key] ?? $default;
    }

    public function only($keys)
    {
        return array_intersect_key($_REQUEST, array_flip($keys));
    }

    public function user()
    {
        return session('user') ?? null;
    }

    public function except($keys)
    {
        return array_diff_key($_REQUEST, array_flip($keys));
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $_REQUEST[$name] = $value;
    }

    public function __toString()
    {
        return json_encode($_REQUEST);
    }
}