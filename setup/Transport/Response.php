<?php

namespace Setup\Transport;

class Response
{
    public function redirect($url)
    {
        header('Location: ' . $url);
    }

    public function back()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function status($code)
    {
        http_response_code($code);
    }

    public function header($key, $value)
    {
        header($key . ': ' . $value);
    }

    public function expectsJson()
    {
        return str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');
    }

    public function expectsHtml()
    {
        return $_SERVER['HTTP_ACCEPT'] === 'text/html';
    }

    public function cookie($name, $value, $time = 3600)
    {
        setcookie($name, $value, time() + $time);
    }

    public function download($file)
    {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        readfile($file);
    }

    public function view($path, $attributes = [])
    {
        extract($attributes);
        return require resource_path("views/{$path}.view.php");
    }

    public function json($data, $code = 200)
    {
        $this->status($code);
        $this->header('Content-Type', 'application/json');
        echo json_encode($data);
    }

    public function abort($code = 404, $message = "Not Found")
    {
        $this->status($code);

        if ($this->expectsJson()) $this->json(['message' => $message], $code);
        else $this->view('errors/' . $code);

        die();
    }
}