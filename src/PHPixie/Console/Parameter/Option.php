<?php

namespace PHPixie\Command\Parameter;

class Option
{
    protected $name;
    protected $isFlag;
    protected $isRequired;
    protected $isShort;
    protected $description;
    
    public function __construct($name)
    {
        $this->name = $name;
        $this->isShort = strlen($name) == 1;
    }

    public function name()
    {
        return $this->name;
    }

    public function isRequired()
    {
        return $this->isRequired;
    }

    public function isShort()
    {
        return $this->isShort;
    }

    public function isFlag()
    {
        return $this->isFlag;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function required()
    {
        $this->isRequired = true;
    }
    
    public function flag()
    {
        $this->isFlag = true;
    }
    
    public function description($description)
    {
        $this->description = $description;
    }
}
