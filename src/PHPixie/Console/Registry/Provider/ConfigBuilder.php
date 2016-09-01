<?php

namespace PHPixie\Console\Registry;

class ConfigBuilder
{
    protected $builder;
    protected $prefix;
    
    public function __construct($builder, $prefix = null)
    {
        $this->builder = $builder;
        $this->prefix = $prefix;
    }
    
    public function config($name)
    {
        return $this->builder->buildCommandConfig($name);
    }
}
