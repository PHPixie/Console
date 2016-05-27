<?php

namespace PHPixie\Command\Commands;

class Option
{

    public function __construct($name, $isRequired, $description, $alias)
    {

    }

    public function name()
    {
        return $this->name;
    }

    public function isRequired()
    {
        return $this->isRequired;
    }

    public function description()
    {
        return $this->description;
    }

    public function alias()
    {
        return $this->name;
    }
}
