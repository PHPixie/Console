<?php

namespace PHPixie\Console\Registry\Provider;

abstract class Implementation implements \PHPixie\Console\Registry\Provider
{
    public function buildCommand($name, $config)
    {
        $method = 'build'.ucfirst($name).'Command';
        return $this->$method($config);
    }
    
    abstract public function commandNames();
}
