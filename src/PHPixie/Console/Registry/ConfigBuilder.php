<?php

namespace PHPixie\Command\Commands;

class ConfigBuilder
{
    protected $builder;
    protected $prefix;
    
    public function __construct($builder, $prefix)
    {
        $this->builder = $builder;
        $this->prefix = $prefix;
    }
    
    public function config()
    {
        $this->builder->commandConfig($this->builder, $this->prefix);
    }
}
