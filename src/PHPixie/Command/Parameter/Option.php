<?php

namespace PHPixie\Command\Parameter;

class Option
{
    protected $name;
    protected $isRequired;
    protected $isShort;
    protected $isFlag;

    public function __construct($name, $isRequired, $isFlag)
    {
        $this->name = $name;
        $this->isRequired = $isRequired;
        $this->isFlag = $isFlag;

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
}
