<?php

namespace PHPixie;

class Console
{
    /**
     * @var Console\Builder
     */
    protected $builder;

    public function __construct($slice, $cli, $registry)
    {
        $this->builder = $this->buildBuilder($slice, $cli, $registry);
    }
    
    public function runCommand()
    {
        $this->builder->runner()->runFromContext();
    }
    
    protected function buildBuilder($slice, $cli, $registry)
    {
        return new Console\Builder($slice, $cli, $registry);
    }
}
