<?php

namespace PHPixie\Console\Context\Container;

class Implementation implements \PHPixie\Console\Context\Container
{
    protected $consoleContext;

    public function __construct($consoleContext)
    {
        $this->consoleContext = $consoleContext;
    }

    public function consoleContext()
    {
        return $this->consoleContext;
    }

    public function setConsoleContext($consoleContext)
    {
        return $this->consoleContext = $consoleContext;
    }
}
