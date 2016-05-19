<?php

namespace PHPixie\Console\Context\Container;

class Impelementation extends PHPixie\Console\Context\Container
{
    protected $consoleContext;

    public function consoleContext()
    {
        return $this->consoleContext;
    }

    public function setConsoleContext($consoleContext)
    {
        return $this->consoleContext = $consoleContext;
    }
}
