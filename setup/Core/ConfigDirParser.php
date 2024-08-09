<?php

namespace Setup\Core;

use Exception;

class ConfigDirParser
{
    protected array $file = [];
    protected array $accessors = [];

    public static function parse(string $accessors)
    {
        $instance = new static;
        $instance->accessors = explode('.', $accessors);
        $instance->file = $instance->validate();

        return $instance;        
    }

    protected function validate()
    {
        if(! count($this->accessors)) throw new Exception("provide at least the config file name");
        
        $file = array_shift($this->accessors);
        if(! file_exists(base_path("config/{$file}.php"))) throw new Exception("Unable to locate config file");

        return require base_path("config/{$file}.php");
    }

    public function get()
    {
        if(count($this->accessors)) return $this->getKeyRec($this->accessors, $this->file);
        else return $this->file;
    }

    function getKeyRec($accessors, $file){
        if(count($accessors) > 1){
            $key = array_shift($accessors);
            if ($file[$key] ?? false) return $this->getKeyRec($accessors, $file[$key]);
        }
        return $file[$accessors[0]] ?? throw new Exception("Unable to define the array key, '{$accessors[0]}'");
    }
}