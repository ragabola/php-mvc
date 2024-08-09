<?php

namespace Setup\Volatile;

class Session
{
    public function start(string $name = "user_session")
    {
        session_start([
            "name" => $name
        ]);
    }

    public function get($key){
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? null;
    }

    public function set($key, $value = null){
        $_SESSION[$key] = $value;
    }

    public function unset($key){
        unset($_SESSION[$key]);
    }

    public function flash($key, $value){
        $_SESSION['_flash'][$key] = $value;
    }

    public function unflash(){
        unset($_SESSION['_flash']);
    }

    public function has($key){
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? false;
    }

    public function regenerate(){
        session_regenerate_id(true);
    }

    public function id(){
        return session_id();
    }

    public function name(){
        return session_name();
    }

    public function all(){
        return $_SESSION;
    }

    public function destroy()
    {
        $_SESSION = [];
        session_destroy();
       
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    public static function __callStatic($name, $arguments)
    {
        return (new self)->$name(...$arguments);
    }
    
    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function __isset($name)
    {
        return $this->has($name);
    }

    public function __unset($name)
    {
        $this->unset($name);
    }
}