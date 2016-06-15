<?php

namespace PHPixie\Command\Parameter;

class Argument
{
    protected $name;
    protected $isRequired;
    protected $isArray;

    public function __construct($name, $isRequired, $isArray)
    {
        $this->name = $name;
        $this->isRequired = $isRequired;
        $this->isArray = $isArray;
    }

    public function name()
    {
        return $this->name;
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
