<?php

namespace PHPixie\Console;

class Parameters
{
    protected $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function options()
    {
        $this->requireParsedArguments();
        return $this->options;
    }

    public function arguments()
    {
        $this->requireParsedArguments();
        return $this->arguments;
    }
}
