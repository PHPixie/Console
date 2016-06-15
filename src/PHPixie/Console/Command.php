<?php

namespace PHPixie\Command\Commands;

use \PHPixie\Console\Exception\InvalidInputException;

class Command
{
    protected $config;
    
    public function __construct($config)
    {
        $config->assertValid();
        $this->config = $config;
    }
    
    abstract public function run($context, $optionData, $argumentData);
}
