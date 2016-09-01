<?php

namespace PHPixie\Console\Command\Config;

class Argument
{
    protected $name;
    protected $description;
    protected $isRequired;
    protected $isArray;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function name()
    {
        return $this->name;
    }
    
    public function description($description)
    {
        $this->description = $description;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function isRequired()
    {
        return $this->isRequired;
    }

    public function isArray()
    {
        return $this->isArray;
    }
}
